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
        Schema::create('asset_components', function (Blueprint $table) {
            $table->id();
    
            $table->enum('parent_type', ['asset', 'package_item']);
            $table->unsignedBigInteger('parent_id');
    
            $table->string('component_key');
            $table->string('component_value');
    
            $table->timestamps();
    
            $table->index(['parent_type', 'parent_id']);
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_components');
    }
};
