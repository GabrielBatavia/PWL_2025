<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function index()
    {
        return view('sales');
    }

    public function processTransaction(Request $request)
    {
        // Dummy response (bisa diganti dengan penyimpanan ke database)
        return back()->with('success', 'Transaction processed successfully!');
    }
}
