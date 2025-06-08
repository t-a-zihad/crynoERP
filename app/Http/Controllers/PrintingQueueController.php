<?php

namespace App\Http\Controllers;

use App\Models\PrintingQueue;
use Illuminate\Http\Request;
use App\Models\CoverPrintingQueue;
use App\Models\BindingQueue;

class PrintingQueueController extends Controller
{
    public function index()
    {
        // Eager load orderedBook and order for priority
        $items = PrintingQueue::with(['orderedBook', 'orderedBook.order'])
            ->orderByDesc('ordered_book_id')
            ->get();

        // attach cover printing queue data manually
        foreach ($items as $item) {
            $coverPrinting = \App\Models\CoverPrintingQueue::where('order_id', $item->order_id)
                ->where('ordered_book_id', $item->ordered_book_id)
                ->first();

            $item->cover_printing_status = $coverPrinting->status ?? 'In Design';
            // Add other properties if needed
        }

        return view('printing-queues.index', compact('items'));
    }

    public function update(Request $request, PrintingQueue $printingQueue)
    {
        $request->validate([
            'status' => 'required|string',
        ]);

        $printingQueue->status = $request->status;
        $printingQueue->save();

        // Fetch related CoverPrintingQueue by order_id and ordered_book_id
        $coverQueue = CoverPrintingQueue::where('order_id', $printingQueue->order_id)
            ->where('ordered_book_id', $printingQueue->ordered_book_id)
            ->first();

        if ($coverQueue && $printingQueue->status === 'Done' && $coverQueue->status === 'Done') {
            $employeeId = session('employee_id'); // or Auth::id()

            // Check if BindingQueue already exists to avoid duplicates
            $exists = BindingQueue::where('order_id', $printingQueue->order_id)
                ->where('ordered_book_id', $printingQueue->ordered_book_id)
                ->exists();

            if (!$exists) {
                BindingQueue::create([
                    'order_id' => $printingQueue->order_id,
                    'ordered_book_id' => $printingQueue->ordered_book_id,
                    'status' => 'In Queue',
                    'handled_by' => $employeeId,
                ]);
            }
        }

        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 5000)
            ->success('Status updated successfully!');

        return redirect()->route('printing-queues.index');
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
            $printingQueue = PrintingQueue::find($id);
            if (!$printingQueue) continue;

            $printingQueue->status = $status;
            $printingQueue->save();

            $coverQueue = CoverPrintingQueue::where('order_id', $printingQueue->order_id)
                ->where('ordered_book_id', $printingQueue->ordered_book_id)
                ->first();

            if ($coverQueue && $status === 'Done' && $coverQueue->status === 'Done') {
                $exists = BindingQueue::where('order_id', $printingQueue->order_id)
                    ->where('ordered_book_id', $printingQueue->ordered_book_id)
                    ->exists();

                if (!$exists) {
                    BindingQueue::create([
                        'order_id' => $printingQueue->order_id,
                        'ordered_book_id' => $printingQueue->ordered_book_id,
                        'status' => 'In Queue',
                        'handled_by' => $employeeId,
                    ]);
                }
            }
        }

        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 8000)
            ->success('Selected printing queue items updated successfully!');

        return redirect()->back();
    }


}
