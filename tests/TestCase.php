<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\Concerns\MakesHttpRequests;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use MakesHttpRequests;

    protected User $user;

    public function authenticatedAdmin(): self
    {
        $this->user = User::factory()->admin()->create();

        return $this->actingAs($this->user, 'sanctum'); // API
    }

    public function authenticated(): self
    {
        $this->user = User::factory()->create();

        return $this->actingAs($this->user, 'sanctum'); // API
    }
}
