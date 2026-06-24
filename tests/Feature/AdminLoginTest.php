<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test admin login page renders and contains language switcher dropdown.
     */
    public function test_admin_login_page_renders_with_language_switcher(): void
    {
        $response = $this->get('/admin/login');

        $response->assertStatus(200);
        $response->assertSee('id="language-switcher"', false);
        $response->assertSee('value="km"', false);
        $response->assertSee('value="en"', false);
    }

    /**
     * Test switching language to Khmer.
     */
    public function test_language_switcher_switches_locale_to_khmer(): void
    {
        $response = $this->get('/lang/km');

        $response->assertRedirect();
        $this->assertEquals('km', session('locale'));
    }

    /**
     * Test switching language to English.
     */
    public function test_language_switcher_switches_locale_to_english(): void
    {
        $response = $this->get('/lang/en');

        $response->assertRedirect();
        $this->assertEquals('en', session('locale'));
    }
}
