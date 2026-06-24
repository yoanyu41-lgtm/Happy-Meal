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
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('has_spice_level')->default(true);
            $table->boolean('has_sweetness_level')->default(false);
            $table->boolean('has_ice_level')->default(false);
            $table->text('addons_config')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['has_spice_level', 'has_sweetness_level', 'has_ice_level', 'addons_config']);
        });
    }
};
