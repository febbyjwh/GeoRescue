<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MitigasiController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.mitigasi.index', [
            'editJalurId' => $request->edit_jalur
        ]);
    }
}
