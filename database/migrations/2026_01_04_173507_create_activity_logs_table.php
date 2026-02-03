<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            // Polymorphic target (Asset / AssetGroup)
            $table->string('subject_type');
            $table->unsignedBigInteger('subject_id');

            // Actor
            $table->foreignId('causer_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('causer_role')->nullable(); // admin / staff

            // Action
            $table->string('event');   // CREATE / UPDATE / APPROVE / REJECT
            $table->string('action');  // human readable

            // Snapshot
            $table->json('before')->nullable();
            $table->json('after')->nullable();

            // Ringkasan perubahan (diff)
            $table->json('changes')->nullable();

            // Metadata
            $table->json('meta')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();

            $table->timestamps();

            // Indexes
            $table->index(['subject_type', 'subject_id']);
            $table->index('event');
            $table->index('causer_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
