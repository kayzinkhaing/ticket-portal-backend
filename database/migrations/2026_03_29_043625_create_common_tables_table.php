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
        Schema::create('common_tables', function (Blueprint $table) {
            $table->id();
            $table->string('type');          // E.g. status, priority, sort_option, error
            $table->string('key');           // E.g. open, high, newest
            $table->string('value');         // Display value like "Open"
            $table->string('label')->nullable();       // Optional display label
            $table->text('description')->nullable();   // Description of the entry
            $table->integer('sort_order')->nullable(); // Sort order for dropdowns
            $table->timestamps();

            $table->index(['type', 'key']); // Fast lookup
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('common_tables');
    }
};
