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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            // Relationship linking to your users table
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Core structural metrics referenced by IndividualAttendanceController
            $table->date('attendance_date');
            $table->timestamp('scanned_at')->useCurrent();
            $table->time('time_in')->nullable();
            $table->time('time_out')->nullable();
            $table->string('status')->default('present'); // e.g., 'present', 'absent', 'late'
            
            $table->timestamps();

            // Indexing performance optimizers for rapid dashboard count queries
            $table->index(['attendance_date', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};