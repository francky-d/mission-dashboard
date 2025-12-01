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
        Schema::create('missions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commercial_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->unsignedInteger('daily_rate')->nullable();
            $table->string('location')->nullable();
            $table->string('status')->default('active'); // active, archived
            $table->timestamps();

            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('missions');
    }
};
