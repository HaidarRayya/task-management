<?php

namespace App\Services;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TaskService
{
    public function allTasks($status, $priority)
    {
        /**
         * get all  tasks
         * @param string $status 
         * @param string $priority 
         * @return   TaskResource $tasks
         */
        try {
            if (Auth::user()->role == 'manager') {
                $tasks = Task::managerTasks(Auth::user()->user_id);
            } else if (Auth::user()->role == 'employee') {
                $tasks = Task::employeeTasks(Auth::user()->user_id);
            } else {
                $tasks = Task::query();
            }
            $tasks = $tasks->byPriority($priority)
                ->byStatus($status)
                ->byDueDate()
                ->get();
            $tasks = TaskResource::collection($tasks);
            return $tasks;
        } catch (Exception $e) {
            Log::error("error in get all tasks" . $e->getMessage());
            throw new Exception("there is something wrong in server");
        }
    }
    /**
     * create  a  new task
     * @param array $data 
     * @return   TaskResource $task
     */
    public function createTask(array $data)
    {

        try {
            $task = Task::create($data);
            $task = TaskResource::make($task);
            return $task;
        } catch (Exception $e) {
            Log::error("error in create task" . $e->getMessage());
            throw new Exception("there is something wrong in server");
        }
    }

    /**
     * show  a  task
     * @param Task $task 
     * @return  TaskResource $task
     */
    public function oneTask($task)
    {
        try {
            $task = TaskResource::make($task);
            return $task;
        } catch (Exception $e) {
            Log::error("error in get a task" . $e->getMessage());
            throw new Exception("there is something wrong in server");
        }
    }
    /**
     * update  a  task
     * @param array $data 
     * @param Task $task 
     * @return  TaskResource $task
     */
    public function updateTask($data, $task)
    {

        try {
            if (Auth::user()->role == 'employee') {
                if ($data['status'] == 'start') {
                    $task->update([
                        'status' => 3
                    ]);
                } else if ($data['status'] == 'end') {
                    $task->update([
                        'status' => 4
                    ]);
                }
            } else {
                $task->update($data);
            }
            $task = TaskResource::make(Task::find($task->task_id));
            return $task;
        } catch (Exception $e) {
            Log::error("error in update a task" . $e->getMessage());
            throw new Exception("there is something wrong in server");
        }
    }
    /**
     * delete  a task
     * @param Task $task 
     */
    public function deleteTask($task)
    {
        if ($task->status == 3) {
            throw new HttpResponseException(response()->json(
                [
                    'status' => 'error',
                    'message' => "لا يمكنك حذف هذه المهمة قد م بدء العمل بها",
                ],
                422
            ));
        } else {
            try {
                $task->delete();
            } catch (Exception $e) {
                Log::error("error in  delete a task"  . $e->getMessage());
                throw new Exception("there is something wrong in server");
            }
        }
    }

    /**
     * get all deleted tasks
     * @param Task $task 
     * @return TaskResource $tasks
     */
    public function allDeletedTask()
    {
        try {
            if (Auth::user()->role == 'manager') {
                $tasks = Task::onlyTrashed()->where('manager_id', '=', Auth::user()->user_id)->get();
            } else {
                $tasks = Task::onlyTrashed()->get();
            }
            $tasks = TaskResource::collection($tasks);
            return $tasks;
        } catch (Exception $e) {
            Log::error("error in  get all deleted tasks"  . $e->getMessage());
            throw new Exception("there is something wrong in server");
        }
    }
    /**
     * restore a task
     * @param int $task_id      
     * @return TaskResource $task
     */
    public function restoreTask($task_id)
    {
        try {
            $task = Task::withTrashed()->find($task_id);
            $task->restore();
            return TaskResource::make($task);
        } catch (Exception $e) {
            Log::error("error in restore a task"  . $e->getMessage());
            throw new Exception("there is something wrong in server");
        }
    }
}
