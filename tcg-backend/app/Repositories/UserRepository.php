<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class UserRepository implements Contracts\UserRepositoryInterface
{

    public function createUser(array $data): User
    {
        $newUser = User::query()->create($data);
        $newUser->assignRole('viewer');
        return $newUser;
    }

    public function updateUser(array $data, $id): bool
    {
        $user = User::query()->find($id);
        if (!$user) {
            return false;
        }
        $user->update($data);
        return true;
    }

    public function deleteUser($id): bool
    {
        $user = User::query()->find($id);
        if (!$user) {
            return false;
        }
        $user->delete();
        return true;
    }

    public function getByEmail(string $name): User
    {
        return User::query()->where('email', '=', $name)->firstOrFail();
    }

    public function getAccessTokenByUserId(int $id): Collection
    {
        return PersonalAccessToken::query()->where('tokenable_id', '=', $id)->get();
    }
}
