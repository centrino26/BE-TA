<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class OrderController extends Controller
{
    public function CreateOrder(Request $request){
        $fields = $request->validate([
            'id_user' => 'required|integer',
            'id_property' => 'required|integer',
            'status' => 'required|string',
            'id_promo' => 'required|integer',
        ]);

        $fields["order_date"] = Carbon::now();


        Order::create($fields);

        return response()->json([
            "message" => "data created"
        ]);
       
    }

    public function UpdateOrder(Request $request, $id){
        $fields = $request->validate([
            'id_user' => 'integer',
            'id_property' => 'integer',
            'status' => 'string',
            'id_promo' => 'integer',
        ]);

        $fields["order_date"] = Carbon::now();


        Order::find($id)->update($fields);

        return response()->json([
            "message" => "data updated"
        ]);
       
    }

    public function DestroyOrder($id){

        Order::destroy($id);

        return response()->json([
            "message" => "data deleted"
        ]);
       
    }

    public function UserOrder($id){
    
    // * mengambil order tiap user
       $data = Order::where('id_user', $id)->with("property")->cursorPaginate(10);
       return response()->json([
        'status' => "success",
        "data" => $data,
    ], 200); 
    }

    
}
