<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    public function test_password_reset_returns_404(): void
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(404);
    }
}
