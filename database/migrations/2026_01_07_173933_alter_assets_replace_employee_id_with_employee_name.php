<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {

            // Tambahkan employee_name
            if (!Schema::hasColumn('assets', 'employee_name')) {
                $table->string('employee_name')->nullable()->after('department_id');
            }

            // Hapus employee_id (jika masih ada)
            if (Schema::hasColumn('assets', 'employee_id')) {
                $table->dropForeign(['employee_id']);
                $table->dropColumn('employee_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->foreignId('employee_id')->nullable()->constrained('users');
            $table->dropColumn('employee_name');
        });
    }
};
