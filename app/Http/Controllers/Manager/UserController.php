<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\FillterUserRequest;
use App\Models\User;
use App\Services\UserService;
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
        $fillter = ['user_name' => $user_name];

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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    /**
     * show a specified user
     *
     * @param User $user 
     *
     * @return response  of the status of operation : user  and tasks
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
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
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
}
