<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('TotalBalance', 10, 2)->nullable(); // Total Balance
            $table->decimal('AmountPaid', 10, 2)->nullable();   // Amount Paid
            $table->decimal('Tax', 10, 2)->nullable();           // Tax
            $table->string('Status')->nullable();                 // Status
            $table->text('AddOns')->nullable();                   // Add Ons
            $table->string('Category')->nullable();               // Category
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['TotalBalance', 'AmountPaid', 'Tax', 'Status', 'AddOns', 'Category']);
        });
    }
};
