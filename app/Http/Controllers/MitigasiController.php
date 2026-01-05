<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Village;
use Illuminate\Http\Request;

class MitigasiController extends Controller
{
    public function index()
    {
        $districts = District::orderBy('name')->get();

        $villages = Village::select('id', 'name', 'district_id')
            ->orderBy('name')
            ->get()
            ->groupBy('district_id');

        return view('Admin.mitigasi.index', compact('districts', 'villages'));
    }

}
