<?php

namespace App\Services;

use App\Enums\TaskStatus;
use App\Http\Resources\TaskResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserService
{
    /**
     * get all  users
     * @param array $fillter
     * @return   UserResource $users
     */
    public function allUsers(array $fillter)
    {
        try {
            if (Auth::user()->role == 'admin') {
                $users = User::notAdmin()
                    ->byRole($fillter['role']);
            } else {
                $users = User::employees();
            }
            $users = $users->byUserName($fillter['user_name'])
                ->get();

            $users = UserResource::collection($users);
            return $users;
        } catch (Exception $e) {
            Log::error("error in get all user" . $e->getMessage());
            throw new Exception("there is something wrong in server");
        }
    }
    /**
     * create  a  new user
     * @param array $data 
     * @return   UserResource $user
     */
    public function createUser($data)
    {
        try {
            $user = new User();
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->password = $data['password'];
            $user->role = $data['role'];
            $user->save();
            $user = UserResource::make($user);
            return $user;
        } catch (Exception $e) {
            Log::error("error in create user" . $e->getMessage());
            throw new Exception("there is something wrong in server");
        }
    }
    /**
     * show  a  user
     * @param User $user 
     * @return  array of  TaskResource $tasks and  UserResource $user 
     */
    public function oneUser($user)
    {
        try {
            if ($user->role == 'employee')
                $tasks = $user->load('employee_tasks')->employee_tasks;
            else if ($user->role == 'manager')
                $tasks = $user->load('tasks')->tasks;
            $tasks = TaskResource::collection($tasks);
            $user = UserResource::make($user);
            return [
                'user' => $user,
                'tasks' => $tasks,
            ];
        } catch (Exception $e) {
            Log::error("error in get a user");
            throw new Exception("there is something wrong in server");
        }
    }

    /**
     * update  a  user
     * @param array $data 
     * @param User $user 
     * @return  UserResource $user
     */
    public function updateUser($data, $user)
    {
        try {
            $user->update($data);
            $user = UserResource::make($user);
            return $user;
        } catch (Exception $e) {
            Log::error("error in get all user");
            throw new Exception("there is something wrong in server");
        }
    }
    /**
     * delete  a user
     * if user is manager change the manager of all own tasks
     * @param User $user 
     */
    public function deleteUser($user)
    {
        try {
            if ($user->role == 'employee')
                $tasks = $user->load('employee_tasks')->employee_tasks;
            else if ($user->role == 'manager')
                $tasks = $user->load('tasks')->tasks;
        } catch (Exception $e) {
            Log::error("error in get all user");
            throw new Exception("there is something wrong in server");
        }
        if ($tasks->isEmpty())
            try {
                $user->delete();
            } catch (Exception $e) {
                Log::error("error in get all user");
                throw new Exception("there is something wrong in server");
            }
        else {
            if ($user->role == 'manager') {
                try {
                    foreach ($tasks as $task)
                        $task->update(['manager_id' => Auth::user()->user_id]);
                    $user->delete();
                } catch (Exception $e) {
                    Log::error("error in get all user");
                    throw new Exception("there is something wrong in server");
                }
            } else {
                throw new HttpResponseException(response()->json(
                    [
                        'status' => 'error',
                        'message' => "لا يمكنك حذف هذا الموظف لديه مهمات موكله له",
                    ],
                    422
                ));
            }
        }
    }
    /**
     * get all deleted users
     * @return UserResource $users
     */

    public function allDeletedUser()
    {
        try {
            $users = User::onlyTrashed()->get();

            $users = UserResource::collection($users);
            return $users;
        } catch (Exception $e) {
            Log::error("error in get all user");
            throw new Exception("there is something wrong in server");
        }
    }
    /**
     * restore a user
     * @param int $user_id      
     * @return UserResource $user
     */
    public function restoreUser($user_id)
    {
        try {
            $user = User::withTrashed()->find($user_id);
            $user->restore();
            return UserResource::make($user);
        } catch (Exception $e) {
            Log::error("error in get all user");
            throw new Exception("there is something wrong in server");
        }
    }
    /**
     * get all available Eemployees
     * @param array $fillter
     * @return   UserResource $users
     */
    public function availableEmployees(array $fillter)
    {

        try {
            $users = User::employees()
                ->byUserName($fillter['user_name'])
                ->with('employee_tasks')
                ->get();
            $x = [];
            foreach ($users as $user) {
                if ($user->employee_tasks->isEmpty()) {
                    array_push($x, $user);
                } else if ($user->employee_tasks[0]->status === 4) {
                    array_push($x, $user);
                }
            }
            $users = UserResource::collection($x);
            return $users;
        } catch (Exception $e) {
            Log::error("error in get available Eemployees" . $e->getMessage());
            throw new Exception("there is something wrong in server");
        }
    }

    /**
     * get all managers
     * @param array $fillter
     * @return   UserResource $users
     */
    public function allManagers(array $fillter)
    {
        try {
            $users = User::managers()
                ->byUserName($fillter['user_name'])
                ->get();
            $users = UserResource::collection($users);
            return $users;
        } catch (Exception $e) {
            Log::error("error in get all managers" . $e->getMessage());
            throw new Exception("there is something wrong in server");
        }
    }
}
