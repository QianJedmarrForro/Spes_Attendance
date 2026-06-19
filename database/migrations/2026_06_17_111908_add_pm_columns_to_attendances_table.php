<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            // AM Slots (Gamitan og time data type para oras lang ang masulod)
            if (!Schema::hasColumn('attendances', 'time_in')) {
                $table->time('time_in')->nullable()->after('attendance_date');
            }
            if (!Schema::hasColumn('attendances', 'time_out')) {
                $table->time('time_out')->nullable()->after('time_in');
            }

            // PM Slots
            if (!Schema::hasColumn('attendances', 'time_in_pm')) {
                $table->time('time_in_pm')->nullable()->after('time_out');
            }
            if (!Schema::hasColumn('attendances', 'time_out_pm')) {
                $table->time('time_out_pm')->nullable()->after('time_in_pm');
            }

            // Live Timeline Sorting Tracker
            if (!Schema::hasColumn('attendances', 'scanned_at')) {
                $table->timestamp('scanned_at')->nullable()->after('time_out_pm');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['time_in', 'time_out', 'time_in_pm', 'time_out_pm', 'scanned_at']);
        });
    }
};