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
        Schema::dropIfExists('asset_package_items');
        Schema::dropIfExists('asset_packages');
    }
    
    public function down(): void
    {
        // optional: recreate jika rollback
    }
    
};
