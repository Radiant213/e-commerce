<?php

namespace Tests\Feature;

use Tests\TestCase;

class CmsApiTest extends TestCase
{
    public function test_can_fetch_cms_bootstrap(): void
    {
        $response = $this->getJson('/api/cms/bootstrap');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'data' => [
                    'settings' => ['site_name', 'site_tagline', 'primary_color', 'accent_color'],
                    'menus',
                    'homepage_sections',
                    'banners',
                    'footer' => ['settings', 'menus'],
                    'popups',
                    'pages_nav',
                ],
            ]);
    }

    public function test_can_fetch_cms_settings(): void
    {
        $response = $this->getJson('/api/cms/settings');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.site_name', 'Radiant Studio');
    }

    public function test_can_fetch_cms_homepage_sections(): void
    {
        $response = $this->getJson('/api/cms/homepage');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'data' => [
                    'sections' => [
                        '*' => ['id', 'type', 'title', 'config', 'sort_order', 'is_active'],
                    ],
                ],
            ]);
    }

    public function test_can_fetch_header_menu(): void
    {
        $response = $this->getJson('/api/cms/menus/header');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'data' => [
                    'name',
                    'location',
                    'items' => [
                        '*' => ['id', 'label', 'url'],
                    ],
                ],
            ]);
    }

    public function test_can_fetch_published_pages(): void
    {
        $response = $this->getJson('/api/cms/pages');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'slug', 'show_in_nav', 'show_in_footer'],
                ],
            ]);
    }

    public function test_can_fetch_single_page_with_blocks(): void
    {
        $response = $this->getJson('/api/cms/pages/about');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.slug', 'about')
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'slug',
                    'blocks' => [
                        '*' => ['id', 'type', 'content'],
                    ],
                ],
            ]);
    }

    public function test_can_fetch_banners(): void
    {
        $response = $this->getJson('/api/cms/banners');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'image', 'placement'],
                ],
            ]);
    }

    public function test_can_fetch_footer_content(): void
    {
        $response = $this->getJson('/api/cms/footer');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'data' => [
                    'settings',
                    'menus',
                ],
            ]);
    }
}
