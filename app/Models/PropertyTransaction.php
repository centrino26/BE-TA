<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PropertyTransaction extends Pivot
{
    protected $fillable = [
        'property_id',
        'user_id',
        "order_id",
        "price",
        "adult_guests",
        "child_guests",
        "date_start",
        "date_end",
        "status",
        "status_transaction"
    ];

    public $timestamps = false;
}
