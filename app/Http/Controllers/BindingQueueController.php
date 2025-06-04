<?php

namespace App\Http\Controllers;

use App\Models\BindingQueue;
use Illuminate\Http\Request;
use App\Models\QcQueue;

class BindingQueueController extends Controller
{
    public function index()
    {
        $queues = BindingQueue::with('orderedBook.order')
            ->orderByDesc('ordered_book_id')
            ->get();

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

}
