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



        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 5000)
            ->success('Status updated successfully!');

        return redirect()->route('cover-printing-queues.index');
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
            $queue = CoverPrintingQueue::find($id);
            if (!$queue) continue;

            $queue->status = $status;
            $queue->save();


        }

        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 8000)
            ->success('Selected cover printing queue items updated successfully!');

        return redirect()->back();
    }


}
