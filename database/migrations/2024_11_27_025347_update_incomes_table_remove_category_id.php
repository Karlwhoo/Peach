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
        Schema::table('incomes', function (Blueprint $table) {
            if (Schema::hasColumn('incomes', 'CategoryID')) {
                $foreignKeys = Schema::getConnection()
                    ->getDoctrineSchemaManager()
                    ->listTableForeignKeys($table->getTable());
                
                foreach ($foreignKeys as $foreignKey) {
                    if (in_array('CategoryID', $foreignKey->getLocalColumns())) {
                        $table->dropForeign($foreignKey->getName());
                    }
                }
                
                $table->dropColumn('CategoryID');
            }
            
            if (!Schema::hasColumn('incomes', 'name')) {
                $table->string('name')->after('id');
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
        Schema::table('incomes', function (Blueprint $table) {
            if (!Schema::hasColumn('incomes', 'CategoryID')) {
                $table->unsignedBigInteger('CategoryID')->nullable();
            }
            
            if (Schema::hasColumn('incomes', 'name')) {
                $table->dropColumn('name');
            }
        });
    }
};
