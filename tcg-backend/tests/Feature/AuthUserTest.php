<?php

namespace Tests\Feature;

use App\Repositories\UserRepository;
use App\Services\Contracts\UserServiceInterface;
use App\Services\UserService;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthUserTest extends TestCase
{
   protected UserServiceInterface $userService;
    public function __construct($name)
    {
        parent::__construct($name);
        $this->userService = new UserService(new UserRepository());
    }

    public function testLogin()
    {
        dd(config('database.default'), env('DB_CONNECTION'), env('APP_ENV'));
        $data = $this->userService->authenticate('admin@example.com', '1qazxsw2');
        $this->assertEquals(200, $data['status']);
    }

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
