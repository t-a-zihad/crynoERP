<?php

namespace App\Http\Controllers;

use App\Models\CoverPrintingQueue;
use Illuminate\Http\Request;
use App\Models\PrintingQueue;
use App\Models\BindingQueue;

class CoverPrintingQueueController extends Controller
{
    public function index()
    {
        // eager load orderedBook and nested order to get priority
        $queues = CoverPrintingQueue::with('orderedBook.order')
            ->orderByDesc('ordered_book_id')
            ->get();

        // attach printing queue data manually
        foreach ($queues as $queue) {
            $printing = \App\Models\PrintingQueue::where('order_id', $queue->order_id)
                ->where('ordered_book_id', $queue->ordered_book_id)
                ->first();

            $queue->printing_status = $printing->status ?? 'N/A';
            // Add other properties if needed, e.g. $queue->printing_handler = $printing->handled_by ?? null;
        }

        return view('cover_printing_queues.index', compact('queues'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
        ]);

        $queue = CoverPrintingQueue::findOrFail($id);
        $queue->status = $request->status;
        $queue->save();

        // Fetch related PrintingQueue by order_id and ordered_book_id
        $printingQueue = PrintingQueue::where('order_id', $queue->order_id)
            ->where('ordered_book_id', $queue->ordered_book_id)
            ->first();

        if ($printingQueue && $queue->status === 'Done' && $printingQueue->status === 'Done') {
            $employeeId = session('employee_id'); // or Auth::id() if using auth

            // Check if BindingQueue already exists to avoid duplicates
            $exists = BindingQueue::where('order_id', $queue->order_id)
                ->where('ordered_book_id', $queue->ordered_book_id)
                ->exists();

            if (!$exists) {
                BindingQueue::create([
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

        return redirect()->route('cover-printing-queues.index');
    }

}
