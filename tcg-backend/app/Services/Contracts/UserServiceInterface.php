<?php

namespace App\Services\Contracts;

use App\Data\AuthenticationOutputData;
use App\Data\UserInputData;
use App\Models\User;

interface UserServiceInterface
{
    public function createUser(UserInputData $inputData) : User;
    public function updateUser(array $data, $id): bool;
    public function deleteUser($id): bool;
    public function authenticate(string $email, string $password): AuthenticationOutputData;
    public function logout(): bool;
}
