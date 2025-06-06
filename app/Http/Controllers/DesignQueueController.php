<?php

namespace App\Http\Controllers;

use App\Models\DesignQueue;
use Illuminate\Http\Request;
use App\Models\CoverPrintingQueue;

class DesignQueueController extends Controller
{
    public function index()
    {
        $designQueues = DesignQueue::with('orderedBook.order')
            ->orderBy('ordered_book_id', 'desc')
            ->get();


        return view('design_queue.index', compact('designQueues'));
    }


    public function show($id)
    {
        $designQueue = DesignQueue::with('orderedBook')->findOrFail($id);
        return view('queues.design.show', compact('designQueue'));
    }

    public function edit($id)
    {
        $designQueue = DesignQueue::findOrFail($id);
        return view('queues.design.edit', compact('designQueue'));
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'status' => 'required|string',
    ]);

    $designQueue = DesignQueue::findOrFail($id);
    $designQueue->status = $request->status;
    $designQueue->save();

    if ($designQueue->status === 'Done' || $designQueue->status === 'Pre-designed') {
        $employeeId = session('employee_id'); // Or Auth::id() if you use Auth

        // Prevent duplicate CoverPrintingQueue entries
        $exists = CoverPrintingQueue::where('order_id', $designQueue->order_id)
            ->where('ordered_book_id', $designQueue->ordered_book_id)
            ->exists();

        if (!$exists) {
            CoverPrintingQueue::create([
                'order_id' => $designQueue->order_id,
                'ordered_book_id' => $designQueue->ordered_book_id,
                'status' => 'In Queue',
                'handled_by' => $employeeId,
            ]);
        }
    }

    flash()
        ->option('position', 'bottom-right')
        ->option('timeout', 10000)
        ->success('Status Updated!');

    return redirect()->back();
}

}
