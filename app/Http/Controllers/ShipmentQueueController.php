<?php

namespace App\Http\Controllers;

use App\Models\ShipmentQueue;
use Illuminate\Http\Request;
use SteadFast\SteadFastCourierLaravelPackage\Facades\SteadfastCourier;
use App\Models\Order;
use App\Models\Status;

class ShipmentQueueController extends Controller
{
    public function index()
    {

        $shipmentQueues = ShipmentQueue::with('order')->orderBy('order_id', 'desc')->get();

        // Add shortStatus and detailedStatus dynamically to each shipmentQueue
        $shipmentQueues = $shipmentQueues->map(function ($shipmentQueue) {

            $steadStatus = SteadfastCourier::checkDeliveryStatusByConsignmentId($shipmentQueue->consignment_id);

            $status;



            if($steadStatus == null){
                $status = 'unknown';
            }else if($steadStatus["status"] == 200){
                $status = $steadStatus["delivery_status"];
            }else{
                $status = 'unknown';
            }

            // Use the Status class to get the short and detailed status
            $statusData = Status::getStatus($status);

            // Append the shortStatus and detailedStatus to the shipmentQueue object
            $shipmentQueue->shortStatus = $statusData->shortStatus;
            $shipmentQueue->detailedStatus = $statusData->statusDetails;

            return $shipmentQueue;
        });

        // Pass the shipmentQueues collection to the view
        return view('shipment-queues.index', compact('shipmentQueues'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
        ]);

        $shipmentQueue = ShipmentQueue::findOrFail($id);

        if($request->status == "Shipped"){
            $order = Order::with('orderedBooks')->where('order_id', $shipmentQueue->order_id)->firstOrFail();

            //COD Calculation
            $booksTotalPrice = $order->orderedBooks->sum(function($book) {
                return $book->unit_price * $book->qty;
            });

            $deliveryCharge = $order->delivery_charge ?? 0;
            $discount = $order->discount ?? 0;

            $grandTotal = $booksTotalPrice + $deliveryCharge - $discount;

            //OrderData To Pass Through SteadfastAPI
            $orderData = [
                'invoice' => $order->order_id,
                'recipient_name' => $order->customer_name,
                'recipient_phone' => $order->phone_number,
                'recipient_address' => $order->shipping_address,
                'cod_amount' => $grandTotal,
                'note' => $order->order_note
            ];

            $response = SteadfastCourier::placeOrder($orderData);

            if($response == null){
                flash()
                ->option('position', 'bottom-right')
                ->option('timeout', 10000)
                ->warning("Error : Couldn't create consignment");
            }elseif($response['status'] == 200){
                $invoice = $response['consignment']['invoice'];
                $consignment_id = $response['consignment']['consignment_id'];
                $tracking_code = $response['consignment']['tracking_code'];

                $shipmentQueue->status = $request->status;
                $shipmentQueue->consignment_id = $consignment_id;
                $shipmentQueue->tracking_code = $tracking_code;
                $shipmentQueue->save();

                flash()
                ->option('position', 'bottom-right')
                ->option('timeout', 10000)
                ->success("Sent To Courier. Order ID: {$invoice}");

            }else{
                flash()
                ->option('position', 'bottom-right')
                ->option('timeout', 10000)
                ->warning("Error {$response['status']}: {$response['message']}");
            }


        }else{
            $shipmentQueue->status = $request->status;
            $shipmentQueue->save();

            flash()
                ->option('position', 'bottom-right')
                ->option('timeout', 3000)
                ->success('Shipment status updated successfully.');
            }




        return redirect()->route('shipment-queues.index');
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'selected_ids' => 'required|string',
            'bulk_status' => 'required|string',
        ]);

        $ids = json_decode($request->selected_ids);
        $status = $request->bulk_status;

        // Loop through each selected shipment queue ID
        foreach ($ids as $id) {
            // Find the shipment queue by ID, if not found, throw a 404 error
            $shipmentQueue = ShipmentQueue::findOrFail($id);

            if ($status == "Shipped") {
                // Retrieve the order with its orderedBooks relation
                $order = Order::with('orderedBooks')->where('order_id', $shipmentQueue->order_id)->firstOrFail();

                // COD Calculation
                $booksTotalPrice = $order->orderedBooks->sum(function ($book) {
                    return $book->unit_price * $book->qty;
                });

                $deliveryCharge = $order->delivery_charge ?? 0;
                $discount = $order->discount ?? 0;

                $grandTotal = $booksTotalPrice + $deliveryCharge - $discount;

                // Prepare order data to pass to SteadfastAPI
                $orderData = [
                    'invoice' => $order->order_id,
                    'recipient_name' => $order->customer_name,
                    'recipient_phone' => $order->phone_number,
                    'recipient_address' => $order->shipping_address,
                    'cod_amount' => $grandTotal,
                    'note' => $order->order_note
                ];

                // Call the SteadfastCourier API to place the order
                $response = SteadfastCourier::placeOrder($orderData);

                if($response == null){
                    flash()
                    ->option('position', 'bottom-right')
                    ->option('timeout', 10000)
                    ->warning("Error : Couldn't create consignment");
                }elseif ($response['status'] == 200) {
                    // If the response is successful, extract the consignment data
                    $invoice = $response['consignment']['invoice'];
                    $consignment_id = $response['consignment']['consignment_id'];
                    $tracking_code = $response['consignment']['tracking_code'];

                    // Update shipmentQueue status and consignment data
                    $shipmentQueue->status = $status;
                    $shipmentQueue->consignment_id = $consignment_id;
                    $shipmentQueue->tracking_code = $tracking_code;
                    $shipmentQueue->save();

                    // Flash success message
                    flash()
                        ->option('position', 'bottom-right')
                        ->option('timeout', 10000)
                        ->success("Sent To Courier. Order ID: {$invoice}");
                } else {
                    // Flash warning if there was an error with the API response
                    flash()
                        ->option('position', 'bottom-right')
                        ->option('timeout', 10000)
                        ->warning("Error {$response['status']}: {$response['message']}");
                }
            } else {
                // If the status is not "Shipped", just update the shipmentQueue status
                $shipmentQueue->status = $status;
                $shipmentQueue->save();

                // Flash success message
                flash()
                    ->option('position', 'bottom-right')
                    ->option('timeout', 3000)
                    ->success('Shipment status updated successfully.');
            }
        }

        return redirect()->route('shipment-queues.index');
    }

}
