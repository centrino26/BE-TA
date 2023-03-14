<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyTransaction;
use Carbon\Carbon;
use Midtrans;
use Midtrans\Snap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Validator;
use Midtrans\Config;

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
            'property_id' => $request->id_property,
            'user_id' => Auth::user()->id,
            'order_id' => $transaction_details["order_id"],
            'price' => $price,
            'adult_guests' => $request->adult_guests,
            'child_guests' => $request->child_guests,
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

    public function notification(Request $request)
    {
        if (!$request->isJson()) {
            error_log("invalid data");
            return response()->json("invalid data", 400);
        }

        $validate = Validator::make($request->all(), [
            'order_id' => 'required',
            'signature_key' => 'required',
            'status_code' => 'required|integer',
            'transaction_status' => 'required|string',
        ]);

        if ($validate->fails()) {
            error_log("invalid value");
            return response()->json("invalid value", 400);
        }

        $transaction = PropertyTransaction::where('order_id', $request->order_id)->first();
        $signature = openssl_digest($transaction->order_id . $transaction->status . $transaction->price . ".00" . Config::$serverKey, "sha512");

        if ($signature != $request->signature_key) {
            error_log($transaction->order_id . $transaction->status . $transaction->price . ".00" . Config::$serverKey);
            error_log($request->order_id . $request->status_code . $request->gross_amount . Config::$serverKey);
            return response()->json("invalid signature", 400);
        }

        // error_log($request->transaction_status);
        switch ($request->transaction_status) {
            case "pending":
                error_log("commit pending");

                PropertyTransaction::where("order_id", $request->order_id)->update([
                    'status' => 200,
                    'status_transaction' => "pending",
                ]);

                break;
            case "settlement":
                error_log("commit settlement");

                PropertyTransaction::where("order_id", $request->order_id)->update([
                    'status' => 200,
                    'status_transaction' => "settlement",
                ]);

                break;
            default:
                return response()->json("invalid status", 400);
        }
    }
}
