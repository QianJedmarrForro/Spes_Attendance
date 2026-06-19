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
            
            // Relationship linking to students table
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            
            // Core structural metrics referenced by DashboardController
            $table->date('attendance_date');
            $table->timestamp('scanned_at')->useCurrent();
            $table->time('time_in')->nullable();
            $table->time('time_out')->nullable();
            $table->string('status')->default('present');
            
            $table->timestamps();

            // Indexing performance optimizers for rapid dashboard count queries
            $table->index(['attendance_date', 'student_id']);
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