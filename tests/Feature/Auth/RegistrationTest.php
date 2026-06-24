<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_daftar_form_can_be_rendered(): void
    {
        $response = $this->get('/daftar');

        $response->assertStatus(200);
    }
}
