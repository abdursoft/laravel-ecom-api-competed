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
        Schema::create('homes', function (Blueprint $table) {
            $table->id();
            $table->string('site_title',100);
            $table->string('short_desc',400);
            $table->longText('meta');
            $table->string('header_logo',400);
            $table->string('footer_logo',300);
            $table->string('footer_text',200);
            $table->string('email',50);
            $table->string('phone',40);
            $table->string('address',140);
            $table->string('country',40);
            $table->string('city',40);
            $table->string('post_code',40);
            $table->string('facebook',250)->nullable();
            $table->string('whatsapp',250)->nullable();
            $table->string('x',250)->nullable();
            $table->string('instagram',250)->nullable();
            $table->string('linkedin',250)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homes');
    }
};
