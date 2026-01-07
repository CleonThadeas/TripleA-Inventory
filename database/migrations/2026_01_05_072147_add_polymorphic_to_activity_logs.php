<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->string('loggable_type')->after('id');
            $table->unsignedBigInteger('loggable_id')->after('loggable_type');
    
            $table->index(['loggable_type', 'loggable_id']);
        });
    }
    
    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropIndex(['loggable_type', 'loggable_id']);
            $table->dropColumn(['loggable_type', 'loggable_id']);
        });
    }
    
};
