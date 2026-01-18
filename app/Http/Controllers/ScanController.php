<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ScanController extends Controller
{
    /**
     * Display the QR code scanner page.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        return view('scan.index');
    }
}
