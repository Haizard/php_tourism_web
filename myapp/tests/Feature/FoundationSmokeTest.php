<?php

namespace Tests\Feature;

use Tests\TestCase;

class FoundationSmokeTest extends TestCase
{
    public function test_login_screen_is_available(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertSee('Email', false);
    }

    public function test_admin_panel_entry_requires_authentication(): void
    {
        $this->get('/admin')
            ->assertRedirect();
    }
}
