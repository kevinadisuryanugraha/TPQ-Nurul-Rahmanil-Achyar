<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    public function test_verify_email_returns_404(): void
    {
        $response = $this->get('/verify-email');

        $response->assertStatus(404);
    }
}
