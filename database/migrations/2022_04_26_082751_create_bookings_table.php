<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->uuid('transaction_id')->unique();
            $table->string('proof_of_payment')->nullable()->default(null);
            $table->string('reference_number')->nullable()->default(null);
            $table->foreignId('user_id')->index();
            $table->foreignId('dispatch_id')->index();
            $table->foreignId('dispatch_route_id')->index();
            $table->tinyInteger('status')->default(1);
            $table->text('remarks')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
};
