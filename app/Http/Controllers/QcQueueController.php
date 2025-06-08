<?php

namespace App\Http\Controllers;

use App\Models\QcQueue;
use Illuminate\Http\Request;
use App\Models\OrderedBook;
use App\Models\PackagingQueue;

class QcQueueController extends Controller
{
    public function index()
    {
        $queues = QcQueue::with('orderedBook.order')
            ->orderByDesc('ordered_book_id')
            ->get();

        return view('qc_queues.index', compact('queues'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
        ]);

        $queue = QcQueue::findOrFail($id);
        $queue->status = $request->status;
        $queue->save();

        $employeeId = session('employee_id'); // or use Auth::id()

        // Get the order id from the updated QC queue
        $orderId = $queue->order_id;

        // Fetch all ordered books for this order
        $orderedBooks = OrderedBook::where('order_id', $orderId)->pluck('ordered_book_id');

        // Fetch QC queue statuses for those ordered books
        $qcStatuses = QcQueue::where('order_id', $orderId)
                        ->whereIn('ordered_book_id', $orderedBooks)
                        ->pluck('status', 'ordered_book_id');

        // Check if any book is missing in qcStatuses (meaning 'In Queue')
        $allDone = true;
        foreach ($orderedBooks as $bookId) {
            if (!isset($qcStatuses[$bookId]) || $qcStatuses[$bookId] !== 'Done') {
                $allDone = false;
                break;
            }
        }

        // If all books QC status is done, create PackagingQueue if not exists
        if ($allDone) {
            $exists = PackagingQueue::where('order_id', $orderId)->exists();
            if (!$exists) {
                PackagingQueue::create([
                    'order_id' => $orderId,
                    'status' => 'In queue',
                    'handled_by' => $employeeId,
                ]);
            }
        }

        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 5000)
            ->success('Status updated successfully!');

        return redirect()->route('qc-queues.index');
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
            $queue = QcQueue::find($id);
            if (!$queue) continue;

            $queue->status = $status;
            $queue->save();

            $orderId = $queue->order_id;

            $orderedBooks = OrderedBook::where('order_id', $orderId)->pluck('ordered_book_id');

            $qcStatuses = QcQueue::where('order_id', $orderId)
                            ->whereIn('ordered_book_id', $orderedBooks)
                            ->pluck('status', 'ordered_book_id');

            $allDone = true;
            foreach ($orderedBooks as $bookId) {
                if (!isset($qcStatuses[$bookId]) || $qcStatuses[$bookId] !== 'Done') {
                    $allDone = false;
                    break;
                }
            }

            if ($allDone) {
                $exists = PackagingQueue::where('order_id', $orderId)->exists();
                if (!$exists) {
                    PackagingQueue::create([
                        'order_id' => $orderId,
                        'status' => 'In queue',
                        'handled_by' => $employeeId,
                    ]);
                }
            }
        }

        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 8000)
            ->success('Selected QC queue items updated successfully!');

        return redirect()->back();
    }


}
