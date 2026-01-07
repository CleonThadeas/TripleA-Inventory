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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
    
            // polymorphic target
            $table->string('subject_type');   // App\Models\Asset | AssetPackage
            $table->unsignedBigInteger('subject_id');
    
            // actor
            $table->foreignId('causer_id')->nullable()
                  ->references('id')->on('users')->nullOnDelete();
            $table->string('causer_role')->nullable();
    
            // action
            $table->string('action'); // create, update, approve, reject, status_change
    
            // snapshots
            $table->json('before')->nullable();
            $table->json('after')->nullable();
            $table->json('meta')->nullable();

            // context (opsional tapi disarankan)
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
    
            $table->timestamps();
    
            $table->index(['subject_type', 'subject_id']);
            $table->index(['action']);
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
