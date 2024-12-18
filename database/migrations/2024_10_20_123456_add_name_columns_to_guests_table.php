<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNameColumnsToGuestsTable extends Migration
{
    public function up()
    {
        Schema::table('guests', function (Blueprint $table) {
            $table->string('Fname')->nullable();
            $table->string('Lname')->nullable();
            $table->string('Mname')->nullable();
        });
    }

    public function down()
    {
        Schema::table('guests', function (Blueprint $table) {
            $table->dropColumn(['Fname', 'Lname', 'Mname']);
        });
    }
}