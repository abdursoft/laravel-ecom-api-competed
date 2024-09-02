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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('vat',100)->default('0');
            $table->string('total',200);
            $table->string('sub_total',200);
            $table->string('discount',200);
            $table->string('payable',200);
            $table->string('trans_id',300);
            $table->string('val_id',10);
            $table->enum('delivery_status',["pending","complete"])->default('pending');
            $table->string('payment_status',100)->default('pending');

            // make the relation with user and payment table
            $table->unsignedBigInteger('profile');
            $table->foreign('profile')->references('id')->on('profiles')->restrictOnDelete()->restrictOnUpdate();

            // make the relation with user and payment table
            $table->unsignedBigInteger('shipping');
            $table->foreign('shipping')->references('id')->on('shippings')->restrictOnDelete()->restrictOnUpdate();

            // make the relation with user and payment table
            $table->unsignedBigInteger('billing');
            $table->foreign('billing')->references('id')->on('billings')->restrictOnDelete()->restrictOnUpdate();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
