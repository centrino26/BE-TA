<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyTransaction;
use Carbon\Carbon;
use Midtrans;
use Midtrans\Snap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Validator;

class MidtransController extends Controller
{
    public function InitiateTransaction(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'adult_guests' => 'required|integer',
            'child_guests' => 'required|integer',
            'id_property' => 'required|integer',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'message' => $validate->errors()->first(),
            ], 400);
        }

        $property = Property::find($request->id_property);

        $date_start = Carbon::createFromFormat("d-m-Y", $request->start_date);
        $date_end = Carbon::createFromFormat("d-m-Y", $request->end_date);

        $price = $property->price * $date_end->diffInDays($date_start);

        $price += collect($property->extra)->sum("harga");

        // return response([number_format($price), number_format($property->price)], 200);

        // $item_details = [
        //     'name' => $property->name_property,
        //     'quantity' => 1,
        //     'price' => $price,
        //     'start_date' => $property->start_date,
        //     'end_date' => $property->end_date,
        //     'adult_guests' => $property->adult_guests,
        //     'child_guests' => $property->child_guests,
        // ];

        $transaction_details = [
            "order_id" => "NUSANTARA-PROPERTY-G524912141-" . rand(10000, 99999),
            "gross_amount" => $price,
        ];

        PropertyTransaction::create([
            'order_id' => $transaction_details["order_id"],
            'price' => $price,
            'adult_guests' => $property->adult_guests,
            'child_guests' => $property->child_guests,
            'date_start' => $date_start,
            'date_end' => $date_end,
        ]);

        $token = Snap::getSnapToken([
            'transaction_details' => $transaction_details,
        ]);

        return response()->json([
            "message" => "success",
            "token" => $token,
        ]);
    }
}
