<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cms\Banner;
use App\Models\Cms\CmsSetting;
use App\Models\Cms\HomepageSection;
use App\Models\Cms\Menu;
use App\Models\Cms\Page;
use App\Models\Cms\Popup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CmsController extends Controller
{
    /**
     * Get all CMS settings for the frontend.
     * Cached for performance.
     */
    public function settings(Request $request): JsonResponse
    {
        $locale = $request->query('lang', 'id');

        $settings = Cache::remember("cms_api_settings_{$locale}", 3600, function () use ($locale) {
            return CmsSetting::getAllForApi($locale);
        });

        return response()->json([
            'success' => true,
            'data' => $settings,
        ]);
    }

    /**
     * Get homepage configuration (all active sections in order).
     */
    public function homepage(Request $request): JsonResponse
    {
        $locale = $request->query('lang', 'id');

        $data = Cache::remember("cms_api_homepage_{$locale}", 1800, function () use ($locale) {
            $sections = HomepageSection::active()
                ->get()
                ->map(fn (HomepageSection $section) => $section->toApiArray($locale))
                ->values()
                ->all();

            return [
                'sections' => $sections,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Get a navigation menu by location.
     */
    public function menu(Request $request, string $location): JsonResponse
    {
        $locale = $request->query('lang', 'id');

        $menu = Cache::remember("cms_api_menu_{$location}_{$locale}", 3600, function () use ($location, $locale) {
            return Menu::getByLocation($location, $locale);
        });

        return response()->json([
            'success' => true,
            'data' => $menu,
        ]);
    }

    /**
     * Get a static page by slug.
     */
    public function page(Request $request, string $slug): JsonResponse
    {
        $locale = $request->query('lang', 'id');

        $page = Cache::remember("cms_api_page_{$slug}_{$locale}", 3600, function () use ($slug, $locale) {
            $page = Page::with(['blocks' => function ($q) {
                $q->where('is_visible', true)->orderBy('sort_order');
            }, 'seoMeta'])
                ->where('slug', $slug)
                ->published()
                ->first();

            return $page?->toApiArray($locale);
        });

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'Page not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $page,
        ]);
    }

    /**
     * Get all published pages (for navigation building).
     */
    public function pages(Request $request): JsonResponse
    {
        $locale = $request->query('lang', 'id');

        $pages = Cache::remember("cms_api_pages_{$locale}", 3600, function () use ($locale) {
            return Page::published()
                ->orderBy('sort_order')
                ->get()
                ->map(function (Page $page) use ($locale) {
                    return [
                        'id' => $page->id,
                        'title' => $page->getTitle($locale),
                        'slug' => $page->slug,
                        'show_in_nav' => $page->show_in_nav,
                        'show_in_footer' => $page->show_in_footer,
                    ];
                })
                ->values()
                ->all();
        });

        return response()->json([
            'success' => true,
            'data' => $pages,
        ]);
    }

    /**
     * Get banners for a specific placement.
     */
    public function banners(Request $request, string $placement = 'homepage'): JsonResponse
    {
        $locale = $request->query('lang', 'id');

        $banners = Cache::remember("cms_api_banners_{$placement}_{$locale}", 1800, function () use ($placement, $locale) {
            return Banner::active()
                ->forPlacement($placement)
                ->orderBy('sort_order')
                ->get()
                ->map(fn (Banner $banner) => $banner->toApiArray($locale))
                ->values()
                ->all();
        });

        return response()->json([
            'success' => true,
            'data' => $banners,
        ]);
    }

    /**
     * Get footer content (settings + trust pillars + social links).
     */
    public function footer(Request $request): JsonResponse
    {
        $locale = $request->query('lang', 'id');

        $data = Cache::remember("cms_api_footer_{$locale}", 3600, function () use ($locale) {
            $footerSettings = CmsSetting::where('group', 'footer')
                ->orWhere('group', 'social')
                ->orWhere('group', 'general')
                ->get()
                ->mapWithKeys(function ($setting) {
                    $value = $setting->value;
                    if ($setting->type === 'json') {
                        $value = json_decode($value, true);
                    }
                    return [$setting->key => $value];
                })
                ->all();

            $footerMenus = [];
            foreach (['footer_col_1', 'footer_col_2', 'footer_col_3'] as $location) {
                $menu = Menu::getByLocation($location, $locale);
                if ($menu) {
                    $footerMenus[] = $menu;
                }
            }

            return [
                'settings' => $footerSettings,
                'menus' => $footerMenus,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Get active popups.
     */
    public function popups(Request $request): JsonResponse
    {
        $locale = $request->query('lang', 'id');

        $popups = Cache::remember("cms_api_popups_{$locale}", 1800, function () use ($locale) {
            return Popup::active()
                ->get()
                ->map(fn (Popup $popup) => $popup->toApiArray($locale))
                ->values()
                ->all();
        });

        return response()->json([
            'success' => true,
            'data' => $popups,
        ]);
    }

    /**
     * Get ALL CMS data in one request (for initial page load).
     * Reduces multiple API calls to a single one.
     */
    public function bootstrap(Request $request): JsonResponse
    {
        $locale = $request->query('lang', 'id');

        $data = Cache::remember("cms_api_bootstrap_{$locale}", 1800, function () use ($locale) {
            $footerSettings = CmsSetting::where('group', 'footer')
                ->orWhere('group', 'social')
                ->orWhere('group', 'general')
                ->get()
                ->mapWithKeys(function ($setting) {
                    $value = $setting->value;
                    if ($setting->type === 'json') {
                        $value = json_decode($value, true);
                    }
                    return [$setting->key => $value];
                })
                ->all();

            $footerMenus = [];
            foreach (['footer_col_1', 'footer_col_2', 'footer_col_3'] as $location) {
                $menu = Menu::getByLocation($location, $locale);
                if ($menu) {
                    $footerMenus[] = $menu;
                }
            }

            return [
                'settings' => CmsSetting::getAllForApi($locale),
                'menus' => [
                    'header' => Menu::getByLocation('header', $locale),
                    'footer_col_1' => Menu::getByLocation('footer_col_1', $locale),
                    'footer_col_2' => Menu::getByLocation('footer_col_2', $locale),
                    'footer_col_3' => Menu::getByLocation('footer_col_3', $locale),
                ],
                'homepage_sections' => HomepageSection::active()
                    ->get()
                    ->map(fn (HomepageSection $s) => $s->toApiArray($locale))
                    ->values()
                    ->all(),
                'banners' => Banner::active()
                    ->forPlacement('homepage')
                    ->orderBy('sort_order')
                    ->get()
                    ->map(fn (Banner $b) => $b->toApiArray($locale))
                    ->values()
                    ->all(),
                'footer' => [
                    'settings' => $footerSettings,
                    'menus' => $footerMenus,
                ],
                'popups' => Popup::active()
                    ->get()
                    ->map(fn (Popup $p) => $p->toApiArray($locale))
                    ->values()
                    ->all(),
                'pages_nav' => Page::published()
                    ->where('show_in_nav', true)
                    ->orderBy('sort_order')
                    ->get()
                    ->map(fn (Page $p) => ['title' => $p->getTitle($locale), 'slug' => $p->slug])
                    ->values()
                    ->all(),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}
