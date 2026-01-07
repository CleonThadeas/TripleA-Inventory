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
        Schema::create('asset_packages', function (Blueprint $table) {
            $table->id();
    
            $table->string('name');
    
            $table->foreignId('employee_id')->nullable()
                  ->references('id')->on('users')->nullOnDelete();
    
            $table->foreignId('department_id')->constrained()->restrictOnDelete();
            $table->foreignId('location_id')->constrained()->restrictOnDelete();
    
            $table->enum('status', [
                'pending',
                'active',
                'maintenance',
                'damaged',
                'lost'
            ])->default('pending');
    
            $table->foreignId('created_by')
                  ->references('id')->on('users')->restrictOnDelete();
    
            $table->foreignId('approved_by')
                  ->nullable()
                  ->references('id')->on('users')->nullOnDelete();
    
            $table->timestamp('approved_at')->nullable();
    
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_packages');
    }
};
