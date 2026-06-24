<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_loads(): void
    {
        $this->get('/')->assertStatus(200);
    }

    public function test_daftar_page_loads(): void
    {
        $this->get('/daftar')->assertStatus(200);
    }

    public function test_login_page_loads(): void
    {
        $this->get('/login')->assertStatus(200);
    }

    public function test_offline_page_loads(): void
    {
        $this->get('/offline')->assertStatus(200);
    }
}
