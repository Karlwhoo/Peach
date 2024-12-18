<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('asset_depreciation_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained()->onDelete('cascade');
            $table->integer('year');
            $table->decimal('starting_value', 15, 2);
            $table->decimal('depreciation_expense', 15, 2);
            $table->decimal('accumulated_depreciation', 15, 2);
            $table->decimal('ending_value', 15, 2);
            $table->date('depreciation_date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('asset_depreciation_schedules');
    }
}; 