<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;

class PasswordUpdateTest extends TestCase
{
    public function test_password_update_returns_404(): void
    {
        $this->put('/password')->assertStatus(404);
    }
}
