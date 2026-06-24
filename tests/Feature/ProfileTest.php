<?php

namespace Tests\Feature;

use Tests\TestCase;

class ProfileTest extends TestCase
{
    public function test_profile_returns_404(): void
    {
        $this->get('/profile')->assertStatus(404);
    }
}
