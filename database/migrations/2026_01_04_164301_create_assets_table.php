<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
        
            $table->string('asset_code')->unique();
            $table->string('name');
        
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->foreignId('location_id')->constrained()->restrictOnDelete();
            $table->foreignId('department_id')->constrained()->restrictOnDelete();
        
            // ✅ FIX UTAMA
            $table->string('employee_name')->nullable();
        
            $table->year('purchase_year');
            $table->string('brand');
            $table->string('model');
        
            $table->string('photo_path')->nullable();
            $table->string('qr_code_path')->nullable();
        
            $table->enum('status', [
                'pending', 'active', 'maintenance', 'damaged', 'lost'
            ])->default('pending');
        
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
        
            $table->timestamps();
        });
        
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
