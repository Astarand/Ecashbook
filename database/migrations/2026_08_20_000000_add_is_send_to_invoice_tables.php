<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        $tables = ['sales', 'quotations', 'proformas', 'purchases', 'puos', 'custom_invoices'];
        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    if (!Schema::hasColumn($tableName, 'is_send')) {
                        $table->tinyInteger('is_send')->default(0)->after('status');
                    }
                });
            }
        }
    }

    public function down()
    {
        $tables = ['sales', 'quotations', 'proformas', 'purchases', 'puos', 'custom_invoices'];
        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    if (Schema::hasColumn($tableName, 'is_send')) {
                        $table->dropColumn('is_send');
                    }
                });
            }
        }
    }
};
