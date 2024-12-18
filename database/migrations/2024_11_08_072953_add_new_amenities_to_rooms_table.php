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
        Schema::table('rooms', function (Blueprint $table) {
            // Check if columns don't exist before adding them
            if (!Schema::hasColumn('rooms', 'DiningArea')) {
                $table->boolean('DiningArea')->default(false);
            }
            if (!Schema::hasColumn('rooms', 'Table')) {
                $table->boolean('Table')->default(false);
            }
            if (!Schema::hasColumn('rooms', 'Chair')) {
                $table->boolean('Chair')->default(false);
            }
            if (!Schema::hasColumn('rooms', 'WiFi')) {
                $table->boolean('WiFi')->default(false);
            }
            if (!Schema::hasColumn('rooms', 'Toilet')) {
                $table->boolean('Toilet')->default(false);
            }
            if (!Schema::hasColumn('rooms', 'Toiletries')) {
                $table->boolean('Toiletries')->default(false);
            }
            if (!Schema::hasColumn('rooms', 'Bathroom')) {
                $table->boolean('Bathroom')->default(false);
            }
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
            // Only drop columns if they exist
            $columns = [
                'DiningArea',
                'Table',
                'Chair',
                'WiFi',
                'Toilet',
                'Toiletries',
                'Bathroom'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('rooms', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
