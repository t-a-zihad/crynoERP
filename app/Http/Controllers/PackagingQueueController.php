<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PackagingQueue;
use App\Models\OrderedBook;
use App\Models\ShipmentQueue;

class PackagingQueueController extends Controller
{
    // Show Packaging Queue list with grouped order_id and related books
    public function index()
    {
        // Get packaging queues grouped by order_id + status (one row per order_id)
        $packagingQueues = PackagingQueue::select('order_id', 'status', 'id')
            ->groupBy('order_id', 'status', 'id')
            ->orderBy('order_id', 'desc')
            ->get();

        // Get order_ids from packaging queues
        $orderIds = $packagingQueues->pluck('order_id')->toArray();

        // Fetch ordered books grouped by order_id
        $orderedBooksGrouped = OrderedBook::whereIn('order_id', $orderIds)
            ->with('order') // eager load order for priority
            ->get()
            ->groupBy('order_id');

        // Pass to view
        return view('packaging-queues.index', compact('packagingQueues', 'orderedBooksGrouped'));
    }

    // Update packaging queue status for a given order_id
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:In queue,Done,Rejected',
        ]);

        // Find the PackagingQueue by id
        $queue = PackagingQueue::findOrFail($id);

        // Update status
        $queue->status = $request->status;
        $queue->save();

        $employeeId = session('employee_id'); // or Auth::id()
        $orderId = $queue->order_id;

        // If status is Done, create ShipmentQueue if not already exists
        if ($queue->status === 'Done') {
            $exists = ShipmentQueue::where('order_id', $orderId)->exists();

            if (!$exists) {
                ShipmentQueue::create([
                    'order_id' => $orderId,
                    'status' => 'In queue',
                    'handled_by' => $employeeId,
                ]);
            }
        }

        // Flash success message
        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 5000)
            ->success('Packaging status updated successfully!');

        return redirect()->route('packaging-queues.index');
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'selected_ids' => 'required|string',
            'bulk_status' => 'required|string|in:In queue,Done,Rejected',
        ]);

        $ids = json_decode($request->selected_ids);
        $status = $request->bulk_status;
        $employeeId = session('employee_id');

        foreach ($ids as $id) {
            $queue = PackagingQueue::find($id);
            if (!$queue) continue;

            $queue->status = $status;
            $queue->save();

            if ($status === 'Done') {
                $orderId = $queue->order_id;
                $exists = ShipmentQueue::where('order_id', $orderId)->exists();
                if (!$exists) {
                    ShipmentQueue::create([
                        'order_id' => $orderId,
                        'status' => 'In queue',
                        'handled_by' => $employeeId,
                    ]);
                }
            }
        }

        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 5000)
            ->success('Selected packaging queue items updated successfully!');

        return redirect()->route('packaging-queues.index');
    }


}
