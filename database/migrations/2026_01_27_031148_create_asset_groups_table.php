<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_groups', function (Blueprint $table) {
            $table->id();

            // Identity
            $table->string('name');
            $table->text('description')->nullable();

            // Ownership
            $table->string('employee_name')->nullable();

            // Approval workflow
            $table->enum('approval_status', [
                'pending',
                'approved',
                'rejected',
            ])->default('pending');

            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('approved_at')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('approval_status');
            $table->index('created_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_groups');
    }
};
