<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class UserInputData extends Data
{
    public function __construct(
        protected string $name,
        protected string $email,
        protected string $password,
    )
    {
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
        ];
    }
}
