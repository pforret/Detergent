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
        Schema::table('settings', function (Blueprint $table) {
            $table->string('work_start_time')->default('09:00');
            $table->string('work_end_time')->default('17:00');
            $table->string('lunch_start_time')->default('13:00');
            $table->string('lunch_end_time')->default('14:00');
            $table->boolean('morning_break_enabled')->default(true);
            $table->string('morning_break_time')->default('11:00');
            $table->boolean('afternoon_break_enabled')->default(true);
            $table->string('afternoon_break_time')->default('16:00');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'work_start_time',
                'work_end_time',
                'lunch_start_time',
                'lunch_end_time',
                'morning_break_enabled',
                'morning_break_time',
                'afternoon_break_enabled',
                'afternoon_break_time',
            ]);
        });
    }
};