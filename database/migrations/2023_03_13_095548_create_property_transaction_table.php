<?php

use App\Models\Property;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_transaction', function (Blueprint $table) {
            $table->foreignIdFor(Property::class);
            $table->string("order_id", Str::length("NUSANTARA-PROPERTY-G524912141-10000"));
            $table->integer("price");
            $table->integer("adult_guests");
            $table->integer("child_guests");
            $table->timestamp("date_start");
            $table->timestamp("date_end");
            $table->enum("status_transaction", ["initiating", "pending", "settlement", "expired", "cancelled"])->default("initiating");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('property_transaction');
    }
};
