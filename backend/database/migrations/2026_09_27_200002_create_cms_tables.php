<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * CMS Core Tables Migration
     * 
     * Creates all foundational tables for the CMS system:
     * - cms_settings: Key-value store for site-wide settings (grouped)
     * - pages: Static pages with SEO and template support
     * - page_blocks: Block-based content for pages
     * - menus: Named navigation menus
     * - menu_items: Individual items within menus (self-referencing for nesting)
     * - banners: Promotional banners with placement targeting
     * - homepage_sections: Configurable homepage section ordering
     * - seo_metas: Polymorphic SEO metadata for any model
     * - popups: Marketing popup/modal configurations
     * - email_templates: Customizable transactional email content
     */
    public function up(): void
    {
        // 1. CMS Settings — key-value store for all site settings
        Schema::create('cms_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('label')->nullable();
            $table->longText('value')->nullable();
            $table->string('type')->default('text'); // text, textarea, number, boolean, json, image, color, code, select
            $table->string('group')->default('general'); // general, branding, announcement, seo, social, contact
            $table->string('tab')->nullable(); // Sub-grouping within a group
            $table->integer('sort_order')->default(0);
            $table->json('options')->nullable(); // For select type: available options
            $table->boolean('is_translatable')->default(false); // Supports multi-lang
            $table->json('translations')->nullable(); // {"en": "...", "id": "..."}
            $table->timestamps();
        });

        // 2. Pages — static pages (About, FAQ, Terms, etc.)
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->json('title_translations')->nullable(); // {"en": "About", "id": "Tentang"}
            $table->string('slug')->unique();
            $table->string('template')->default('default'); // default, full-width, with-sidebar
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->boolean('show_in_nav')->default(false);
            $table->boolean('show_in_footer')->default(false);
            $table->integer('sort_order')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        // 3. Page Blocks — block-based content within pages
        Schema::create('page_blocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // heading, text, image, accordion, tabs, embed, gallery, cta, divider, code
            $table->json('content'); // Block-specific data (varies by type)
            $table->json('content_translations')->nullable(); // Multi-lang content
            $table->json('settings')->nullable(); // Block-level styling/config
            $table->integer('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });

        // 4. Menus — named menu containers
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // "Main Navigation", "Footer Links", etc.
            $table->string('location')->unique(); // header, footer_col_1, footer_col_2, footer_col_3, mobile
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 5. Menu Items — individual links within a menu
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('menu_items')->nullOnDelete(); // Nested menus
            $table->string('label');
            $table->json('label_translations')->nullable();
            $table->string('url')->nullable(); // External URL or internal path
            $table->string('type')->default('custom'); // custom, page, category, product
            $table->unsignedBigInteger('linkable_id')->nullable(); // Polymorphic link target
            $table->string('linkable_type')->nullable();
            $table->string('target')->default('_self'); // _self, _blank
            $table->string('icon')->nullable(); // Lucide icon name
            $table->integer('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });

        // 6. Banners — promotional banners
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->json('title_translations')->nullable();
            $table->text('subtitle')->nullable();
            $table->json('subtitle_translations')->nullable();
            $table->string('image'); // Desktop image
            $table->string('image_mobile')->nullable(); // Mobile-specific image
            $table->string('alt_text')->nullable();
            $table->string('link')->nullable();
            $table->string('placement')->default('homepage'); // homepage, category, product, sidebar
            $table->string('cta_text')->nullable();
            $table->json('cta_text_translations')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
        });

        // 7. Homepage Sections — ordered sections configuration
        Schema::create('homepage_sections', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // hero, categories, product_showcase, brand_story, newsletter, custom_banner
            $table->string('title')->nullable();
            $table->json('config'); // Section-specific configuration (JSON blob)
            $table->json('config_translations')->nullable(); // Translatable config fields
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 8. SEO Metas — polymorphic SEO data (attachable to Page, Product, Category, etc.)
        Schema::create('seo_metas', function (Blueprint $table) {
            $table->id();
            $table->morphs('seoable'); // seoable_id + seoable_type
            $table->string('meta_title')->nullable();
            $table->json('meta_title_translations')->nullable();
            $table->text('meta_description')->nullable();
            $table->json('meta_description_translations')->nullable();
            $table->string('og_image')->nullable();
            $table->string('canonical_url')->nullable();
            $table->json('extra_meta')->nullable(); // Custom meta tags as key-value
            $table->timestamps();
        });

        // 9. Popups — marketing modals
        Schema::create('popups', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Internal name for identification
            $table->string('title')->nullable();
            $table->json('title_translations')->nullable();
            $table->text('description')->nullable();
            $table->json('description_translations')->nullable();
            $table->string('image')->nullable();
            $table->string('type')->default('welcome'); // welcome, exit_intent, timed, scroll
            $table->string('cta_text')->nullable();
            $table->json('cta_text_translations')->nullable();
            $table->string('cta_link')->nullable();
            $table->integer('delay_seconds')->default(3);
            $table->boolean('show_once_per_session')->default(true);
            $table->boolean('is_active')->default(false);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
        });

        // 10. Email Templates — customizable email content
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Internal name
            $table->string('slug')->unique(); // order_confirmation, shipping_notification, welcome
            $table->string('subject');
            $table->json('subject_translations')->nullable();
            $table->text('header_text')->nullable();
            $table->json('header_text_translations')->nullable();
            $table->longText('body_content')->nullable();
            $table->json('body_content_translations')->nullable();
            $table->text('footer_text')->nullable();
            $table->json('footer_text_translations')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_templates');
        Schema::dropIfExists('popups');
        Schema::dropIfExists('seo_metas');
        Schema::dropIfExists('homepage_sections');
        Schema::dropIfExists('banners');
        Schema::dropIfExists('menu_items');
        Schema::dropIfExists('menus');
        Schema::dropIfExists('page_blocks');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('cms_settings');
    }
};
