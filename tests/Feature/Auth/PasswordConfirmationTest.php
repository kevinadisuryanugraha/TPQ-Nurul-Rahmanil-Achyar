<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;

class PasswordConfirmationTest extends TestCase
{
    public function test_confirm_password_returns_404(): void
    {
        $response = $this->get('/confirm-password');

        $response->assertStatus(404);
    }
}
