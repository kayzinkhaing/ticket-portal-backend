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
       Schema::table('tickets', function ($table) {
        $table->boolean('created_notified')->default(false);
        $table->boolean('closed_notified')->default(false);
        $table->boolean('overdue_notified')->default(false);
        $table->boolean('duesoon_notified')->default(false);
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            //
        });
    }
};
