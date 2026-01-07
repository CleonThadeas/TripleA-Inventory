<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {

            // HAPUS pola lama jika ada
            if (Schema::hasColumn('activity_logs', 'subject_type')) {
                $table->dropColumn('subject_type');
            }

            if (Schema::hasColumn('activity_logs', 'subject_id')) {
                $table->dropColumn('subject_id');
            }

            // TAMBAH pola Laravel standard
            if (!Schema::hasColumn('activity_logs', 'loggable_type')) {
                $table->string('loggable_type')->after('id');
            }

            if (!Schema::hasColumn('activity_logs', 'loggable_id')) {
                $table->unsignedBigInteger('loggable_id')->after('loggable_type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            // rollback TIDAK direkomendasikan untuk audit log
        });
    }
};
