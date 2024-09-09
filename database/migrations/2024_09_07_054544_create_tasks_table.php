<?php

use App\Enums\TaskPriority;
use App\Models\User;
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
        Schema::create('employee_tasks', function (Blueprint $table) {
            $table->id('task_id');
            $table->string('title');
            $table->string('description');
            $table->enum('priority', array_column(TaskPriority::cases(), 'value'));
            $table->timestamp('due_date');
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->foreign('assigned_to')->references('user_id')->on('users');
            $table->unsignedBigInteger('manager_id');
            $table->foreign('manager_id')->references('user_id')->on('users');
            $table->unsignedTinyInteger('status')->default(1);
            /*
            1  new 
            2  appointed 
            3  started
            4  ended
            5  falied
            */
            $table->timestamp('created_date');
            $table->timestamp('updated_date');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};