<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ssl_commerzes', function (Blueprint $table) {
            $table->id();
            $table->string('payment_name',250);
            $table->string('store_id',250);
            $table->string('store_password',250);
            $table->string('currency',10);
            $table->string('success_url',400);
            $table->string('fail_url',400);
            $table->string('cancel_url',400);
            $table->string('ipn_url',500);
            $table->string('init_url',500);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ssl_commerzes');
    }
};
