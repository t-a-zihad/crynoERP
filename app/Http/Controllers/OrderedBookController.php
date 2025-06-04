<?php

namespace App\Http\Controllers;

use App\Models\OrderedBook;
use Illuminate\Http\Request;
use App\Http\Controllers\OrderedBookController;

class OrderedBookController extends Controller
{
    public function index()
    {
        // Eager load all related data for efficiency
        $books = OrderedBook::with([
            'order',
            'designQueue',
            'printingQueue',
            'coverPrintingQueue',
            'bindingQueue',
            'qcQueue'
        ])->orderBy('created_at', 'desc')->get();

        return view('ordered-books.index', compact('books'));
    }
}
