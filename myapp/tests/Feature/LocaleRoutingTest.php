<?php

namespace Tests\Feature;

use Tests\TestCase;

class LocaleRoutingTest extends TestCase
{
    public function test_root_redirects_to_default_locale(): void
    {
        $this->get('/')
            ->assertRedirect('/en');
    }

    public function test_english_home_page_renders_with_ltr_direction(): void
    {
        $this->get('/en')
            ->assertOk()
            ->assertSee('Tourism Starter Kit')
            ->assertSee('lang="en"', false)
            ->assertSee('dir="ltr"', false);
    }

    public function test_arabic_home_page_renders_with_rtl_direction(): void
    {
        $this->get('/ar')
            ->assertOk()
            ->assertSee('lang="ar"', false)
            ->assertSee('dir="rtl"', false);
    }

    public function test_unsupported_locale_returns_not_found(): void
    {
        $this->get('/zz')
            ->assertNotFound();
    }

    public function test_placeholder_pages_render_under_supported_locale(): void
    {
        foreach (['about', 'tours', 'destinations', 'blog', 'gallery', 'faq', 'contact', 'privacy', 'terms'] as $page) {
            $this->get("/en/{$page}")
                ->assertOk()
                ->assertSee('Tourism Starter Kit');
        }
    }
}
