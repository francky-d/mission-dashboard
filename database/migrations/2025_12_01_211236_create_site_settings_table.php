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
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();

            // Branding
            $table->string('site_name')->default('Mission Dashboard');
            $table->string('logo_path')->nullable();

            // Consultant theme colors (default: blue)
            $table->string('consultant_primary_color')->default('#3B82F6');
            $table->string('consultant_secondary_color')->default('#1E40AF');
            $table->string('consultant_accent_color')->default('#60A5FA');

            // Commercial theme colors (default: orange)
            $table->string('commercial_primary_color')->default('#F97316');
            $table->string('commercial_secondary_color')->default('#C2410C');
            $table->string('commercial_accent_color')->default('#FB923C');

            $table->timestamps();
        });

        // Insert default settings
        DB::table('site_settings')->insert([
            'site_name' => 'Mission Dashboard',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
