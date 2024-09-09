<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\FillterUserRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\Task;
use App\Models\User;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    /**
     * Display a listing of the resource.
     */
    /**
     * get all uasrs
     *
     * @param FillterUserRequest $request 
     *
     * @return response  of the status of operation : users  
     */
    public function index(FillterUserRequest $request)
    {
        $user_name = $request->input('user_name');
        $role = $request->input('role');
        $fillter = ['user_name' => $user_name, 'role' => $role];
        $users = $this->userService->allUsers($fillter);
        return response()->json([
            'status' => 'success',
            'data' => [
                'users' =>  $users
            ],
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * create a new uaser
     *
     * @param StoreUserRequest $request 
     *
     * @return response  of the status of operation : user and message
     */
    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->createUser($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'تم انشاء المستخدم بنجاح',
            'data' => [
                'user' =>  $user
            ],
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    /**
     * show a specified user
     *
     * @param User $user 
     *
     * @return response  of the status of operation : user 
     */
    public function show(User $user)
    {
        $data = $this->userService->oneUser($user);

        return response()->json([
            'status' => 'success',
            'data' => [
                ...$data
            ],
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * update a specified task
     * @param UpdateUserRequest $request
     * @param User $user
     *
     * @return response  of the status of operation : user and message 
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user = $this->userService->updateUser($request->all(), $user);

        return response()->json([
            'status' => 'success',
            'message' => 'تم تحديث المستخدم بنجاح',
            'data' => [
                'user' =>  $user
            ],
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */


    /**
     * delete a specified user
     * @param User $user 
     *
     * @return response  of the status of operation 
     */
    public function destroy(User $user)
    {
        $this->userService->deleteUser($user);
        return response()->json([
            'status' => 'success',
        ], 204);
    }
    /**
     * get all deleted users
     *
     *
     * @return response  of the status of operation : users  
     */
    public function allDeletedUsers()
    {
        $users = $this->userService->allDeletedUser();
        return response()->json([
            'status' => 'success',
            'data' => [
                'users' =>  $users
            ],
        ], 200);
    }

    /**
     * restore a  user
     *
     * @param int $user_id 
     *
     * @return response  of the status of operation : user
     */
    public function restoreUser($user_id)
    {
        $user = $this->userService->restoreUser($user_id);
        return response()->json([
            'status' => 'success',
            'data' => [
                'user' =>  $user
            ],
        ], 200);
    }
    /**
     * get all available employees
     *
     * @param FillterUserRequest $request 
     *
     * @return response  of the status of operation : users  
     */
    public function allAvailableEmployees(FillterUserRequest $request)
    {
        $user_name = $request->input('user_name');
        $fillter = ['user_name' => $user_name];

        $users = $this->userService->availableEmployees($fillter);
        return response()->json([
            'status' => 'success',
            'data' => [
                'users' =>  $users
            ],
        ], 200);
    }
    /**
     * get all Managers
     *
     * @param FillterUserRequest $request 
     *
     * @return response  of the status of operation : users  
     */
    public function allManager(FillterUserRequest $request)
    {
        $user_name = $request->input('user_name');
        $fillter = ['user_name' => $user_name];
        $users = $this->userService->allManagers($fillter);
        return response()->json([
            'status' => 'success',
            'data' => [
                'users' =>  $users
            ],
        ], 200);
    }
}
