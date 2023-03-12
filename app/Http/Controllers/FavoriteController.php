<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $fields = $request->validate([
            'id_property' => 'required|integer',
        ]);

        $favorite = Favorite::create(
            [
                "id_property" => $fields['id_property'],
                "id_user" => Auth::id()
            ]
        );

        return response()->json([
            'status' => "success",
            "data" => $favorite,
        ], 200);
    }

    public function getFavorite()
    {

        $favorite = Favorite::where('id_user', Auth::id())->get();

        return response()->json([
            'status' => "success",
            "data" => $favorite,
        ], 200);

    }
}
