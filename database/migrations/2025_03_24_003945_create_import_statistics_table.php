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
        Schema::create('import_statistics', function (Blueprint $table) {
            $table->foreignId('import_id')->constrained('import_jobs')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('total_records');
            $table->integer('processed_records');
            $table->integer('failed_records');
            $table->float('success_rate');
            $table->integer('duration');
            $table->float('records_per_second');
            $table->timestamp('completed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_statistics');
    }
};
