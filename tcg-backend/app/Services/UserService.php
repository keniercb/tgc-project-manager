<?php

namespace App\Services;

use App\Data\AuthenticationOutputData;
use App\Data\UserInputData;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Contracts\UserServiceInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UserService implements UserServiceInterface
{

    public function __construct(protected UserRepositoryInterface $userRepository)
    {

    }

    public function createUser(UserInputData $inputData): User
    {
        $data = $inputData->toArray();
        $data['created_at'] = Carbon::now();
        $data['updated_at'] = Carbon::now();
        $data['created_by'] = auth()->user()->name;
        return $this->userRepository->createUser($data);
    }

    public function updateUser(array $data, $id): bool
    {
        return $this->userRepository->updateUser($data, $id);
    }

    public function deleteUser($id): bool
    {
        return $this->userRepository->deleteUser($id);
    }

    public function authenticate(string $email, string $password): AuthenticationOutputData
    {
        $user = $this->userRepository->getByEmail($email);
        if (Hash::check($password, $user->password)) {
            $token = explode('|', $user->createToken('authToken', ['api:consume'])->plainTextToken);
            return new AuthenticationOutputData(
                $user->email,
                $user->id,
                'test',
                $token[1],
                $user->name,
                200
            );
        }
        return new AuthenticationOutputData(
            '', 0, '', '', 401
        );
    }

    public function logout(): bool
    {
        auth()->user()->tokens()->delete();
        return true;
    }
}
