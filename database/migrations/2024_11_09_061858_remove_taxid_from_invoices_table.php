<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveTaxidFromInvoicesTable extends Migration
{
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('TaxID');
        });
    }

    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('TaxID')->nullable();
        });
    }
}