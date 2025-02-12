<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quote;

class QuoteController extends Controller
{
    // Show all quotes in the admin panel
    public function index()
    {
        $quotes = Quote::latest()->paginate(10);
        return view('admin.quotes.index', compact('quotes'));
    }
}
