<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveNameColumnFromGuestsTable extends Migration
{
    public function up()
    {
        Schema::table('guests', function (Blueprint $table) {
            $table->dropColumn('Name');
        });
    }

    public function down()
    {
        Schema::table('guests', function (Blueprint $table) {
            $table->string('Name')->nullable();
        });
    }
}
