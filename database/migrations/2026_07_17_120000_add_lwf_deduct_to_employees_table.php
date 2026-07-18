<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLwfDeductToEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('employees', 'lwf_deduct')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->decimal('lwf_deduct', 12, 2)->default(0)->after('tds');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('employees', 'lwf_deduct')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->dropColumn('lwf_deduct');
            });
        }
    }
}
