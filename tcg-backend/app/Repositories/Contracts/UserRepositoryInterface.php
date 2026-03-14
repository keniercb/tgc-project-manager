<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    public function createUser(array $data): User;

    public function updateUser(array $data, $id): bool;

    public function deleteUser($id): bool;

    public function getByEmail(string $name): User;

    public function getAccessTokenByUserId(int $id) : Collection;
}
