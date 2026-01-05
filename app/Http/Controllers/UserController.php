<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        return view('user.index');
    }
    public function bencana()
    {
        return Bencana::select(
            'id','nama_bencana','tingkat_kerawanan',
            'lat','lang','kecamatan_id','desa_id'
        )->get();
    }

    public function posko()
    {
        return PoskoBencana::select(
            'id','nama_posko','jenis_posko',
            'latitude','longitude','status_posko'
        )->get();
    }
}
