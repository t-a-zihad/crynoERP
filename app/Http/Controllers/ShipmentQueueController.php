<?php

namespace App\Http\Controllers;

use App\Models\ShipmentQueue;
use Illuminate\Http\Request;

class ShipmentQueueController extends Controller
{
    public function index()
    {
        // Eager load order relation to get customer details
        $shipmentQueues = ShipmentQueue::with('order')->orderBy('order_id', 'desc')->get();

        return view('shipment-queues.index', compact('shipmentQueues'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
        ]);

        $shipmentQueue = ShipmentQueue::findOrFail($id);
        $shipmentQueue->status = $request->status;
        $shipmentQueue->save();

        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 3000)
            ->success('Shipment status updated successfully.');

        return redirect()->route('shipment-queues.index');
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'selected_ids' => 'required|string',
            'bulk_status' => 'required|string|in:In queue,Shipping,Done,Rejected',
        ]);

        $ids = json_decode($request->selected_ids);
        $status = $request->bulk_status;

        ShipmentQueue::whereIn('id', $ids)->update(['status' => $status]);

        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 3000)
            ->success('Selected shipment queue items updated successfully!');

        return redirect()->route('shipment-queues.index');
    }

}
