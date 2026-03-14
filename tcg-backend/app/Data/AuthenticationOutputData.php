<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class AuthenticationOutputData extends Data
{
    public function __construct(
        public string $email,
        public int    $id,
        public string $role,
        public string $token,
        public string $userName,
        public int    $status = 403,
    )
    {
    }

    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'id' => $this->id,
            'role' => $this->role,
            'token' => $this->token,
            'status' => $this->status,
            'user' => $this->userName,
        ];
    }
}
