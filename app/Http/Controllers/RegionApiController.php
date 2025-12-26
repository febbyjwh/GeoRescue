<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\District;
use App\Models\Village;

class RegionApiController extends Controller
{
    public function districts(Request $request)
    {
        $search = $request->q;

        $data = District::when($search, function ($q) use ($search) {
            $q->where('name', 'like', "%$search%");
        })
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($data);
    }

    public function villages(Request $request)
    {
        $request->validate([
            'district_id' => 'required'
        ]);

        $search = $request->q;

        $data = Village::where('district_id', $request->district_id)
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            })
            ->orderBy('name')
            ->get(['id', 'name', 'district_id']);

        return response()->json($data);
    }
}
