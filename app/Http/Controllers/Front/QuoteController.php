<?php

namespace App\Http\Controllers\Front;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quote;
use DB;

class QuoteController extends Controller
{
    public function index()
    {
        $g_setting = DB::table('general_settings')->where('id', 1)->first();
        return view('pages.quote', compact('g_setting'));
    }

    // Store the quote form submission
    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'country' => 'required|string|max:255',
            'business_type' => 'required|in:Business,Individual',
            'looking_for' => 'required|array|min:1',
            'looking_for.*' => 'in:Batteries,Spare Parts,Lubricants,Others',
            'message' => 'nullable|string',
        ]);

        Quote::create($request->all());

        return redirect()->back()->with('success', 'Your quote request has been submitted successfully.');
    }
}
