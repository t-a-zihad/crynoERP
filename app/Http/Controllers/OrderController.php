<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderedBook;
use App\Models\DesignQueue;
use App\Models\PrintingQueue;
use App\Models\CoverPrintingQueue;
use App\Models\BindingQueue;
use App\Models\QcQueue;
use App\Models\PackagingQueue;
use App\Models\ShipmentQueue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function index()
    {
        // Eager load relationships
        $orders = Order::with([
            'orderedBooks',       // For book count and prices
            'packagingQueue',     // For status
            'shipmentQueue',      // For status
            'handledBy'           // For manager name (employee)
        ])->orderBy('created_at', 'desc')->get();

        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        // Fetch only employees with role 'order manager' (Handled By)
        $employees = \App\Models\Employee::where('role', 'order manager')->get();
        return view('orders.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_priority' => 'required|string',
            'customer_name' => 'required|string',
            'phone_number' => 'required|string',
            'shipping_address' => 'required|string',
            'delivery_type' => 'required|string',
            'discount' => 'nullable|numeric',
            'order_note' => 'nullable|string',
            'books' => 'required|array|min:1',
            'books.*.book_name' => 'required|string',
            'books.*.book_author' => 'nullable|string',
            'books.*.binding_type' => 'required|string',
            'books.*.special_note' => 'nullable|string',
            'books.*.custom_cover' => 'nullable|boolean',
            'books.*.unit_price' => 'required|numeric',
            'books.*.qty' => 'required|integer|min:1',
        ]);

        // Generate order id
        $datePrefix = 'CRY_O-' . now()->format('Y-m');
        $lastOrder = Order::where('order_id', 'like', $datePrefix . '%')->orderBy('order_id', 'desc')->first();
        $newNumber = '00001';
        if ($lastOrder) {
            $lastNumber = (int)substr($lastOrder->order_id, -5);
            $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        }
        $orderId = $datePrefix . '-' . $newNumber;

        // Calculate delivery charge
        $deliveryCharge = match ($request->delivery_type) {
            'Free' => 0,
            'Inside Dhaka' => 70,
            'Outside Dhaka' => 110,
            default => 0,
        };

        // Create order
        $order = Order::create([
            'order_id' => $orderId,
            'order_priority' => $request->order_priority,
            'customer_name' => $request->customer_name,
            'phone_number' => $request->phone_number,
            'shipping_address' => $request->shipping_address,
            'delivery_type' => $request->delivery_type,
            'delivery_charge' => $deliveryCharge,
            'discount' => $request->discount ?? 0,
            'order_note' => $request->order_note,
            'handled_by' => $request->handled_by, // or other logic for manager id
        ]);

        $employeeId = session('employee_id'); // from logged-in user

        // Create books with generated ordered_book_id
        foreach ($request->books as $index => $bookData) {
            $bookNumber = str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            $orderedBookId = $orderId . '-B-' . $bookNumber;

            OrderedBook::create([
                'ordered_book_id' => $orderedBookId,
                'order_id' => $orderId,
                'book_name' => $bookData['book_name'],
                'book_author' => $bookData['book_author'] ?? null,
                'binding_type' => $bookData['binding_type'],
                'special_note' => $bookData['special_note'] ?? null,
                'custom_cover' => $bookData['custom_cover'] ?? false,
                'unit_price' => $bookData['unit_price'],
                'qty' => $bookData['qty'],
            ]);




            // Initialize all queues with default 'in queue' status
            DesignQueue::create([
                'order_id' => $orderId,
                'ordered_book_id' => $orderedBookId,
                'status' => 'In Queue',
                'handled_by' => $employeeId,
            ]);

            PrintingQueue::create([
                'order_id' => $orderId,
                'ordered_book_id' => $orderedBookId,
                'status' => 'In Queue',
                'handled_by' => $employeeId,
            ]);




        }



        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 5000)
            ->success('Order Created successfully!');

        return redirect()->route('orders.index');
    }

    public function show($orderId)
    {
        $order = Order::with('orderedBooks', 'designQueue', 'printingQueue', 'coverPrintingQueue', 'bindingQueue', 'qcQueue', 'packagingQueue', 'shipmentQueue')->where('order_id', $orderId)->firstOrFail();
        $employees = Employee::where('role', 'order manager')->get();

        return view('orders.show', compact('order', 'employees'));
    }

    public function edit($orderId)
    {
        $order = Order::with('orderedBooks')->where('order_id', $orderId)->firstOrFail();
        $employees = Employee::where('role', 'order manager')->get();

        return view('orders.edit', compact('order', 'employees'));
    }


    public function update(Request $request, $orderId)
    {
        $request->validate([
            'order_priority' => 'required|string',
            'customer_name' => 'required|string',
            'phone_number' => 'required|string',
            'shipping_address' => 'required|string',
            'delivery_type' => 'required|string',
            'discount' => 'nullable|numeric',
            'order_note' => 'nullable|string',
            'books' => 'required|array|min:1',
            'books.*.book_name' => 'required|string',
            'books.*.book_author' => 'nullable|string',
            'books.*.binding_type' => 'required|string',
            'books.*.special_note' => 'nullable|string',
            'books.*.custom_cover' => 'nullable|boolean',
            'books.*.unit_price' => 'required|numeric',
            'books.*.qty' => 'required|integer|min:1',
        ]);

        $order = Order::where('order_id', $orderId)->firstOrFail();

        $deliveryCharge = match ($request->delivery_type) {
            'Free' => 0,
            'Inside Dhaka' => 70,
            'Outside Dhaka' => 110,
            default => 0,
        };

        $order->update([
            'order_priority' => $request->order_priority,
            'customer_name' => $request->customer_name,
            'phone_number' => $request->phone_number,
            'shipping_address' => $request->shipping_address,
            'delivery_type' => $request->delivery_type,
            'delivery_charge' => $deliveryCharge,
            'discount' => $request->discount ?? 0,
            'order_note' => $request->order_note,
            'handled_by' => $request->handled_by,
        ]);

        $employeeId = session('employee_id');
        $existingBooks = $order->orderedBooks->keyBy('ordered_book_id');

        foreach ($request->books as $index => $bookData) {
            $bookId = $bookData['ordered_book_id'] ?? null;

            if ($bookId && $existingBooks->has($bookId)) {
                // Update existing book
                $existingBooks[$bookId]->update([
                    'book_name' => $bookData['book_name'],
                    'book_author' => $bookData['book_author'] ?? null,
                    'binding_type' => $bookData['binding_type'],
                    'special_note' => $bookData['special_note'] ?? null,
                    'custom_cover' => $bookData['custom_cover'] ?? false,
                    'unit_price' => $bookData['unit_price'],
                    'qty' => $bookData['qty'],
                ]);
            } else {
                // Add new book
                $bookNumber = str_pad($index + 1, 3, '0', STR_PAD_LEFT);
                $newBookId = $orderId . '-B-' . $bookNumber;

                OrderedBook::create([
                    'ordered_book_id' => $newBookId,
                    'order_id' => $orderId,
                    'book_name' => $bookData['book_name'],
                    'book_author' => $bookData['book_author'] ?? null,
                    'binding_type' => $bookData['binding_type'],
                    'special_note' => $bookData['special_note'] ?? null,
                    'custom_cover' => $bookData['custom_cover'] ?? false,
                    'unit_price' => $bookData['unit_price'],
                    'qty' => $bookData['qty'],
                ]);

                // Initialize queues
                DesignQueue::create([
                    'order_id' => $orderId,
                    'ordered_book_id' => $newBookId,
                    'status' => 'In Queue',
                    'handled_by' => $employeeId,
                ]);

                PrintingQueue::create([
                    'order_id' => $orderId,
                    'ordered_book_id' => $newBookId,
                    'status' => 'In Queue',
                    'handled_by' => $employeeId,
                ]);

                //Order Overall status updaing
                $packagingQueue = PackagingQueue::where('order_id', $orderId)->firstOrFail();
                $shipmentQueue = ShipmentQueue::where('order_id', $orderId)->firstOrFail();

                $packagingQueue->update([
                    'status' => 'In Progress'
                ]);

                $shipmentQueue->update([
                    'status' => 'In Progress'
                ]);
            }
        }

        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 5000)
            ->success('Order updated successfully!');

        return redirect()->route('orders.index');
    }



    public function generateInvoice($orderId)
    {
        $order = Order::with('orderedBooks')->where('order_id', $orderId)->firstOrFail();
        //$pdf = Pdf::loadView('orders.invoice', compact('order'));
        //return $pdf->download('invoice_' . $orderId . '.pdf');

        return view('orders.invoice', compact('order'));
    }

    public function destroy($id)
    {
        // Find the order by ID
        $order = Order::where('order_id', $id)->firstOrFail();

        // Delete associated books and queues
        $order->orderedBooks()->delete(); // Deleting related books
        $order->designQueue()->delete();
        $order->printingQueue()->delete();
        $order->coverPrintingQueue()->delete();
        $order->bindingQueue()->delete();
        $order->qcQueue()->delete();
        $order->packagingQueue()->delete();
        $order->shipmentQueue()->delete();

        // Delete the order itself
        $order->delete();

        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 5000)
            ->success('Order deleted successfully!');
        return redirect()->route('orders.index');
    }







}
