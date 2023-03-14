<?php

use App\Models\Property;
use App\Models\User;
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
            $table->foreignIdFor(User::class);
            $table->string("order_id", Str::length("NUSANTARA-PROPERTY-G524912141-10000"))->unique();
            $table->bigInteger("price");
            $table->integer("adult_guests");
            $table->integer("child_guests");
            $table->timestamp("date_start")->nullable();
            $table->timestamp("date_end")->nullable();
            $table->integer("status")->default(201);
            $table->string("status_transaction")->default("initiating");
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
