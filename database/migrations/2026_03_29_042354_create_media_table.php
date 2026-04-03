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
        Schema::create('media', function (Blueprint $table) {
            $table->id();

            // Polymorphic relation
            $table->string('mediable_type', 100);
            $table->unsignedBigInteger('mediable_id');

            // File information
            $table->string('url', 255);                     // Stored (hashed) file path
            $table->string('original_filename')->nullable(); // Original user-uploaded filename
            $table->string('mime_type', 100)->nullable();   // MIME type, e.g., image/jpeg
            $table->unsignedBigInteger('size')->nullable(); // File size in bytes


            // Upload timestamp
            $table->timestamp('uploaded_at')->useCurrent();

            // Optional: index for faster polymorphic queries
            $table->index(['mediable_type', 'mediable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
