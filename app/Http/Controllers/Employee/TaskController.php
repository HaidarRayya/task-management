<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\EmployeeUpdateRequest;
use App\Http\Requests\Task\FillterTaskRequest;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    protected $taskService;
    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
        $this->authorizeResource(Task::class, 'task');
    }
    /**
     * Display a listing of the resource.
     */
    /**
     * get all tasks
     *
     * @param FillterTaskRequest $request 
     *
     * @return response  of the status of operation : tasks  
     */
    public function index(FillterTaskRequest $request)
    {
        $status = $request->input('status');
        $priority = $request->input('priority');
        $tasks = $this->taskService->allTasks($status, $priority);

        return response()->json([
            'status' => 'success',
            'data' => [
                'tasks' =>  $tasks
            ],
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, User $user)
    {
        //
    }

    /**
     * Display the specified resource.
     */

    /**
     * show a specified task
     * @param  User $user
     * @param Task $task 
     *
     * @return response  of the status of operation : task 
     */
    public function show(User $user, Task $task)
    {
        $task = $this->taskService->oneTask($task);

        return response()->json([
            'status' => 'success',
            'data' => [
                'task' =>  $task
            ],
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * update a specified task
     * @param EmployeeUpdateRequest $request
     * @param  User $user
     * @param Task $task 
     *
     * @return response  of the status of operation : task and message 
     */
    public function update(EmployeeUpdateRequest $request, User $user, Task $task)
    {
        $task =  $this->taskService->updateTask($request->all(), $task);
        return response()->json([
            'status' => 'success',
            'data' => [
                'task' =>  $task
            ],
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user, Task $task)
    {
        //
    }
}
