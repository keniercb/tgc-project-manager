<?php

namespace App\Http\Controllers\Api;

use App\Data\UserInputData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AuthenticationRequest;
use App\Http\Requests\Api\CreateUserRequest;
use App\Services\Contracts\UserServiceInterface;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(protected UserServiceInterface $userService)
    {

    }

    public function create(CreateUserRequest $request)
    {
        $userInputData = new UserInputData(
            $request->input('name'),
            $request->input('email'),
            $request->input('password'),
        );
        $createUser = $this->userService->createUser($userInputData);
        return response()->json(
            [
                'user' => $createUser->toArray(),
            ]
        );
    }

    public function authenticate(AuthenticationRequest $request)
    {
        $authOutput = $this->userService->authenticate($request->input('email'), $request->input('password'));
        return response()->json($authOutput->toArray(), $authOutput->status);
    }

    public function logout()
    {
        $this->userService->logout();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function info()
    {

    }
}
