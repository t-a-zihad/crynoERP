<?php

namespace App\Http\Controllers;

use App\Models\BindingQueue;
use Illuminate\Http\Request;
use App\Models\QcQueue;
use App\Models\CoverPrintingQueue;

class BindingQueueController extends Controller
{
    public function index()
    {
        $queues = BindingQueue::with('orderedBook.order')
            ->orderByDesc('ordered_book_id')
            ->get();

        foreach ($queues as $queue) {
            $coverPrinting = CoverPrintingQueue::where('order_id', $queue->order_id)
                ->where('ordered_book_id', $queue->ordered_book_id)
                ->first();

            $queue->cover_printing_status = $coverPrinting->status ?? 'In Design';
            // Add other properties if needed
        }

        return view('binding_queues.index', compact('queues'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
        ]);

        $queue = BindingQueue::findOrFail($id);
        $queue->status = $request->status;
        $queue->save();

        if ($queue->status === 'Done') {
            $employeeId = session('employee_id'); // Or use Auth::id() if applicable

            // Check if QcQueue already exists to avoid duplicates
            $exists = QcQueue::where('order_id', $queue->order_id)
                ->where('ordered_book_id', $queue->ordered_book_id)
                ->exists();

            if (!$exists) {
                QcQueue::create([
                    'order_id' => $queue->order_id,
                    'ordered_book_id' => $queue->ordered_book_id,
                    'status' => 'In Queue',
                    'handled_by' => $employeeId,
                ]);
            }
        }

        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 5000)
            ->success('Status updated successfully!');

        return redirect()->route('binding-queues.index');
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'selected_ids' => 'required|string',
            'bulk_status' => 'required|string'
        ]);

        $ids = json_decode($request->selected_ids);
        $status = $request->bulk_status;
        $employeeId = session('employee_id');

        foreach ($ids as $id) {
            $queue = BindingQueue::find($id);
            if (!$queue) continue;

            $queue->status = $status;
            $queue->save();

            if ($status === 'Done') {
                $exists = QcQueue::where('order_id', $queue->order_id)
                    ->where('ordered_book_id', $queue->ordered_book_id)
                    ->exists();

                if (!$exists) {
                    QcQueue::create([
                        'order_id' => $queue->order_id,
                        'ordered_book_id' => $queue->ordered_book_id,
                        'status' => 'In Queue',
                        'handled_by' => $employeeId,
                    ]);
                }
            }
        }

        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 8000)
            ->success('Selected binding queue items updated successfully!');

        return redirect()->back();
    }


}
