<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add new columns to tasks
        if (!Schema::hasColumn('tasks', 'type')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->string('type')->default('minor')->after('description'); 
                $table->string('requester')->nullable()->after('type');
                $table->string('origin')->nullable()->after('requester');
            });

            // 2. Migrate existing tasks priority to type
            if (Schema::hasColumn('tasks', 'priority')) {
                DB::table('tasks')->where('priority', 'major')->update(['type' => 'major']);
                DB::table('tasks')->where('priority', 'minor')->update(['type' => 'minor']);
            }
            
            // 3. Migrate interrupts to tasks
            if (Schema::hasTable('interrupts')) {
                $interrupts = DB::table('interrupts')->get();
                foreach ($interrupts as $interrupt) {
                    $taskId = DB::table('tasks')->insertGetId([
                        'day_id' => $interrupt->day_id,
                        'title' => $interrupt->title,
                        'type' => 'interrupt',
                        'requester' => $interrupt->requester,
                        'origin' => $interrupt->origin,
                        'estimated_minutes' => $interrupt->duration,
                        'status' => 'done', 
                        'created_at' => $interrupt->created_at,
                        'updated_at' => $interrupt->updated_at,
                    ]);
                    
                    // Update linked time_blocks
                    if (Schema::hasColumn('time_blocks', 'interrupt_id')) {
                        DB::table('time_blocks')->where('interrupt_id', $interrupt->id)->update([
                            'task_id' => $taskId,
                            'interrupt_id' => null
                        ]);
                    }
                }
            }
        }

        // 4. Drop priority column from tasks
        if (Schema::hasColumn('tasks', 'priority')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->dropColumn('priority');
            });
        }
        
        // 5. Cleanup time_blocks
        if (Schema::hasColumn('time_blocks', 'interrupt_id')) {
             if (DB::getDriverName() === 'sqlite') {
                 // Manual table rebuild for SQLite to avoid FK issues
                 Schema::create('time_blocks_temp', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('day_id')->constrained()->onDelete('cascade');
                    $table->foreignId('task_id')->nullable()->constrained()->onDelete('set null');
                    $table->time('start_time');
                    $table->time('end_time');
                    $table->string('description')->nullable();
                    $table->timestamps();
                });
                
                $blocks = DB::table('time_blocks')->get();
                foreach ($blocks as $block) {
                    DB::table('time_blocks_temp')->insert([
                        'id' => $block->id,
                        'day_id' => $block->day_id,
                        'task_id' => $block->task_id,
                        'start_time' => $block->start_time,
                        'end_time' => $block->end_time,
                        'description' => $block->description,
                        'created_at' => $block->created_at,
                        'updated_at' => $block->updated_at,
                    ]);
                }
                
                Schema::drop('time_blocks');
                Schema::rename('time_blocks_temp', 'time_blocks');
             } else {
                Schema::table('time_blocks', function (Blueprint $table) {
                    $table->dropForeign(['interrupt_id']);
                    $table->dropColumn('interrupt_id');
                });
             }
        }

        // 6. Drop interrupts table
        Schema::dropIfExists('interrupts');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Simplified down
    }
};