<?php

namespace App\Http\Controllers\Manager;

use App\Enums\TaskStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Task\FillterTaskRequest;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;

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
    /**
     * create a new task
     * @param  User $user
     * @param StoreTaskRequest $request 
     *
     * @return response  of the status of operation : task and message
     */
    public function store(StoreTaskRequest $request, User $user)
    {

        $task = $this->taskService->createTask($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'تم انشاء المهمة بنجاح',
            'data' => [
                'task' =>  $task
            ],
        ], 201);
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
     * @param  User $user
     * @param UpdateTaskRequest $request
     * @param Task $task 
     *
     * @return response  of the status of operation : task and message 
     */
    public function update(UpdateTaskRequest $request, User $user, Task $task)
    {
        $task = $this->taskService->updateTask($request->all(), $task);

        return response()->json([
            'status' => 'success',
            'message' => 'تم تحديث المهمة بنجاح',
            'data' => [
                'task' =>  $task
            ],
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */

    /**
     * delete a specified task
     * @param  User $user
     * @param Task $task 
     *
     * @return response  of the status of operation 
     */
    public function destroy(User $user, Task $task)
    {
        $this->taskService->deleteTask($task);
        return response()->json([
            'status' => 'success',
        ], 204);
    }
    /**
     * get all deleted task
     * @param  User $user
     * @return response  of the status of operation : task 
     */
    public function allDeletedTasks(User $user)
    {
        $tasks = $this->taskService->allDeletedTask();
        return response()->json([
            'status' => 'success',
            'data' => [
                'tasks' =>  $tasks
            ],
        ], 200);
    }
    /**
     * restore a specified task
     * @param int $task_id      
     * @return response  of the status of operation : task 
     */
    public function restoreTask($task_id)
    {
        $task = $this->taskService->restoreTask($task_id);
        return response()->json([
            'status' => 'success',
            'data' => [
                'task' =>  $task
            ],
        ], 200);
    }
}