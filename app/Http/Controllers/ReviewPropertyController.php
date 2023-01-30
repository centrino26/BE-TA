<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property_Rating;

class ReviewPropertyController extends Controller
{
    public function review($id)
    {
        $review = Property_Rating::where('id_property',$id)->cursorPaginate(10);
        return response()->json([
            'status' => "success",
            "data" => $review,
        ], 200);   
    }
}
