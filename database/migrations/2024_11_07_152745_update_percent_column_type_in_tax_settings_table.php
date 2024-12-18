<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePercentColumnTypeInTaxSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tax_settings', function (Blueprint $table) {
            // Change the type of Percent column to varchar
            $table->string('Percent', 255)->nullable()->change(); // Change to varchar
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tax_settings', function (Blueprint $table) {
            // Change back to decimal if rolling back
            $table->decimal('Percent', 8, 2)->nullable()->change(); // Change back to decimal
        });
    }
}
