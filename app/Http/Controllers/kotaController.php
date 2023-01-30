<?php

namespace App\Http\Controllers;

use App\Models\Kota;
use App\Models\Provinsi;
use Illuminate\Http\Request;

class kotaController extends Controller
{
    public function list($id)
    {
        $kota = Kota::where('intprovinsi_id', $id)->cursorPaginate(100);

         return response()->json([
            'status' => "success",
            "data" => $kota,
        ], 200); 
    }

    public function list_provinsi()
    {
        $provinsi = Provinsi::cursorPaginate(100);
        return response()->json([
            'status' => "success",
            "data" => $provinsi,
        ], 200); 
    }
}
