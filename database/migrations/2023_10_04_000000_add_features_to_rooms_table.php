<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFeaturesToRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->boolean('DiningArea')->nullable();
            $table->boolean('Table')->nullable();
            $table->boolean('Chair')->nullable();
            $table->boolean('WiFi')->nullable();
            $table->boolean('Toilet')->nullable();
            $table->boolean('Toiletries')->nullable();
            $table->boolean('Bathroom')->nullable();
            $table->boolean('TVSet')->nullable();
            $table->boolean('AirConditioning')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn(['DiningArea', 'Table', 'Chair', 'WiFi', 'Toilet', 'Toiletries', 'Bathroom', 'TVSet', 'AirConditioning']);
        });
    }
}; 