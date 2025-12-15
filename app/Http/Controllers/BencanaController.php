<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BencanaController extends Controller
{
    public function index()
    {
        return view('admin.bencana.index');
    }
}
