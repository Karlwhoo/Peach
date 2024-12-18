<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_number')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->date('purchase_date');
            $table->decimal('purchase_cost', 15, 2);
            $table->decimal('asset_cost', 15, 2);
            $table->integer('useful_life');
            $table->decimal('salvage_value', 15, 2);
            $table->string('serial_number')->nullable();
            $table->string('category')->nullable();
            $table->string('status')->default('Active');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('assets');
    }
}; 