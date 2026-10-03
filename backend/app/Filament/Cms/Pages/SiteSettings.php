<?php

namespace App\Filament\Cms\Pages;

use App\Models\Cms\CmsSetting;
use App\Support\CmsCache;
use BackedEnum;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use UnitEnum;

class SiteSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Identitas & Kontak';
    protected static ?string $title = 'Identitas & Kontak Toko';
    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan Toko';
    protected static ?int $navigationSort = 1;
    protected string $view = 'filament.cms.pages.site-settings';

    public ?array $settings = [];

    public function mount(): void
    {
        $data = $this->loadSettings();
        $this->form->fill($data);
    }

    protected function loadSettings(): array
    {
        $defaults = $this->getDefaultSettings();
        $saved = CmsSetting::all()->pluck('value', 'key')->toArray();

        $result = [];
        foreach ($defaults as $key => $default) {
            $result[$key] = $saved[$key] ?? $default;
        }

        if (isset($result['footer_trust_pillars']) && is_string($result['footer_trust_pillars'])) {
            $result['footer_trust_pillars'] = json_decode($result['footer_trust_pillars'], true) ?: [];
        }

        return $result;
    }

    protected function getDefaultSettings(): array
    {
        return [
            // General & Branding
            'site_name' => 'Radiant Studio',
            'site_tagline' => 'Premium E-Commerce Experience',
            'site_logo' => null,
            'site_favicon' => null,
            'site_email' => 'hello@radiantcode.web.id',
            'site_phone' => '+6287878444402',
            'site_whatsapp' => '+6287878444402',
            'site_address' => '',
            'site_currency' => 'IDR',
            'copyright_text' => '© {year} Radiant Studio E-Commerce. All Rights Reserved.',
            'powered_by_text' => 'Powered by Laravel 11 API & React',
            'maintenance_mode' => '0',

            // Announcement Bar
            'announcement_active' => '1',
            'announcement_text' => '🚚 Gratis Ongkir ke Seluruh Indonesia — Belanja Sekarang!',
            'announcement_text_en' => '🚚 Free Shipping Across Indonesia — Shop Now!',
            'announcement_link' => '/products',
            'announcement_bg_color' => '#0f172a',
            'announcement_text_color' => '#ffffff',

            // Social Links
            'social_whatsapp' => 'https://wa.me/+6287878444402',
            'social_instagram' => 'https://instagram.com/radofdiant',
            'social_twitter' => 'https://twitter.com/radiant213_',
            'social_github' => 'https://github.com/Radiant213',
            'social_tiktok' => '',
            'social_youtube' => '',

            // Footer
            'footer_about_desc' => 'Marketplace premium Indonesia dengan koleksi produk berkualitas tinggi. Kami mengutamakan kualitas, keaslian, dan pengalaman belanja yang menyenangkan.',
            'footer_about_desc_en' => 'Premium Indonesian marketplace with high-quality product collections. We prioritize quality, authenticity, and a delightful shopping experience.',
            'footer_payment_methods' => 'MIDTRANS,QRIS,BCA / MANDIRI,GOPAY / OVO',

            // Trust Pillars
            'footer_trust_pillars' => [
                ['icon' => 'Truck', 'title' => 'Gratis Ongkir', 'title_en' => 'Free Shipping', 'subtitle' => 'Seluruh Indonesia', 'subtitle_en' => 'Across Indonesia'],
                ['icon' => 'ShieldCheck', 'title' => '100% Original', 'title_en' => '100% Authentic', 'subtitle' => 'Produk Terverifikasi', 'subtitle_en' => 'Verified Products'],
                ['icon' => 'RotateCcw', 'title' => 'Garansi 30 Hari', 'title_en' => '30-Day Guarantee', 'subtitle' => 'Uang Kembali', 'subtitle_en' => 'Money Back'],
                ['icon' => 'Headphones', 'title' => 'CS 24/7', 'title_en' => 'CS 24/7', 'subtitle' => 'Siap Membantu', 'subtitle_en' => 'Ready to Help'],
            ],
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('settings')
            ->components([
                Tabs::make('Pengaturan CMS')
                    ->columnSpanFull()
                    ->tabs([
                        // Tab 1: General & Logo
                        Tab::make('Profil & Logo Toko')
                            ->icon('heroicon-o-building-storefront')
                            ->schema([
                                Section::make('Logo & Ikon Toko')
                                    ->description('Upload logo untuk ditampilkan di navbar dan favicon tab browser.')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            FileUpload::make('site_logo')
                                                ->label('Logo Toko (Navbar)')
                                                ->image()
                                                ->disk('public')
                                                ->directory('cms/branding')
                                                ->visibility('public')
                                                ->maxSize(2048)
                                                ->helperText('Format PNG/SVG/WebP latar transparan. Ideal: 250 × 60 px.'),

                                            FileUpload::make('site_favicon')
                                                ->label('Favicon (Tab Browser)')
                                                ->image()
                                                ->disk('public')
                                                ->directory('cms/branding')
                                                ->visibility('public')
                                                ->maxSize(512)
                                                ->helperText('Format PNG/ICO persegi. Ideal: 32 × 32 px atau 64 × 64 px.'),
                                        ]),
                                    ]),

                                Section::make('Informasi Dasar Toko')
                                    ->description('Pengaturan nama, slogan, dan kontak resmi yang tampil di website.')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('site_name')
                                                ->label('Nama Toko')
                                                ->required()
                                                ->maxLength(100),
                                            TextInput::make('site_tagline')
                                                ->label('Slogan / Tagline')
                                                ->maxLength(200),
                                        ]),
                                        Grid::make(2)->schema([
                                            TextInput::make('site_whatsapp')
                                                ->label('WhatsApp Customer Service')
                                                ->helperText('Contoh: +6287878444402'),
                                            TextInput::make('site_email')
                                                ->label('Email Bantuan')
                                                ->email(),
                                        ]),
                                        Textarea::make('site_address')
                                            ->label('Alamat Fisik Toko')
                                            ->rows(3),
                                    ]),
                            ]),

                        // Tab 2: Announcement Bar
                        Tab::make('Promo Berjalan (Navbar)')
                            ->icon('heroicon-o-megaphone')
                            ->schema([
                                Section::make('Banner Pengumuman Atas')
                                    ->description('Bar pengumuman di bagian paling atas halaman sebelum navbar.')
                                    ->schema([
                                        Toggle::make('announcement_active')
                                            ->label('Tampilkan Announcement Bar')
                                            ->default(true),
                                        Grid::make(2)->schema([
                                            TextInput::make('announcement_text')
                                                ->label('Teks Pengumuman (ID)')
                                                ->required()
                                                ->maxLength(255),
                                            TextInput::make('announcement_text_en')
                                                ->label('Teks Pengumuman (EN)')
                                                ->maxLength(255),
                                        ]),
                                        TextInput::make('announcement_link')
                                            ->label('Link Tujuan (Opsional)')
                                            ->placeholder('/products atau https://...'),
                                        Grid::make(2)->schema([
                                            ColorPicker::make('announcement_bg_color')
                                                ->label('Warna Background'),
                                            ColorPicker::make('announcement_text_color')
                                                ->label('Warna Teks'),
                                        ]),
                                    ]),
                            ]),

                        // Tab 3: Social Media
                        Tab::make('Sosial Media')
                            ->icon('heroicon-o-share')
                            ->schema([
                                Section::make('Tautan Media Sosial')
                                    ->description('Link akun sosial media toko yang tampil di footer.')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('social_whatsapp')
                                                ->label('WhatsApp Link')
                                                ->url()
                                                ->placeholder('https://wa.me/...'),
                                            TextInput::make('social_instagram')
                                                ->label('Instagram')
                                                ->url()
                                                ->placeholder('https://instagram.com/...'),
                                        ]),
                                        Grid::make(2)->schema([
                                            TextInput::make('social_twitter')
                                                ->label('Twitter / X')
                                                ->url()
                                                ->placeholder('https://twitter.com/...'),
                                            TextInput::make('social_github')
                                                ->label('GitHub')
                                                ->url()
                                                ->placeholder('https://github.com/...'),
                                        ]),
                                        Grid::make(2)->schema([
                                            TextInput::make('social_tiktok')
                                                ->label('TikTok')
                                                ->url()
                                                ->placeholder('https://tiktok.com/@...'),
                                            TextInput::make('social_youtube')
                                                ->label('YouTube')
                                                ->url()
                                                ->placeholder('https://youtube.com/@...'),
                                        ]),
                                    ]),
                            ]),

                        // Tab 4: Footer & Trust Pillars
                        Tab::make('Tampilan Footer')
                            ->icon('heroicon-o-queue-list')
                            ->schema([
                                Section::make('Keunggulan Toko (Trust Pillars)')
                                    ->description('4 poin garansi / keunggulan yang tampil di bagian atas footer.')
                                    ->schema([
                                        Repeater::make('footer_trust_pillars')
                                            ->label('Poin Keunggulan')
                                            ->schema([
                                                Grid::make(3)->schema([
                                                    Select::make('icon')
                                                        ->label('Ikon')
                                                        ->options([
                                                            'Truck' => '🚚 Truk / Pengiriman',
                                                            'ShieldCheck' => '🛡️ Perisai / 100% Original',
                                                            'RotateCcw' => '🔄 Putar / Garansi Retur',
                                                            'Headphones' => '🎧 Headphone / CS 24/7',
                                                            'Award' => '🏅 Penghargaan / Kualitas',
                                                            'Star' => '⭐ Bintang / Terpercaya',
                                                            'Lock' => '🔒 Gembok / Belanja Aman',
                                                            'CreditCard' => '💳 Kartu / Pembayaran Mudah',
                                                            'Gift' => '🎁 Hadiah / Bonus Promo',
                                                            'Clock' => '⏱️ Jam / Pengiriman Cepat',
                                                        ])
                                                        ->default('Truck')
                                                        ->native(false)
                                                        ->required(),

                                                    TextInput::make('title')
                                                        ->label('Judul (ID)')
                                                        ->required()
                                                        ->placeholder('Gratis Ongkir'),

                                                    TextInput::make('subtitle')
                                                        ->label('Keterangan (ID)')
                                                        ->placeholder('Seluruh Indonesia'),
                                                ]),
                                                Grid::make(2)->schema([
                                                    TextInput::make('title_en')
                                                        ->label('Judul (EN opsional)')
                                                        ->placeholder('Free Shipping'),
                                                    TextInput::make('subtitle_en')
                                                        ->label('Keterangan (EN opsional)')
                                                        ->placeholder('Across Indonesia'),
                                                ]),
                                            ])
                                            ->defaultItems(4)
                                            ->collapsible()
                                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null),
                                    ]),

                                Section::make('Konten Bagian Bawah (Footer)')
                                    ->description('Informasi singkat toko dan metode pembayaran resmi.')
                                    ->schema([
                                        Textarea::make('footer_about_desc')
                                            ->label('Deskripsi Singkat Toko di Footer')
                                            ->rows(3)
                                            ->placeholder('Platform e-commerce premium terpercaya...'),
                                        TextInput::make('footer_payment_methods')
                                            ->label('Daftar Metode Pembayaran (Pisahkan koma)')
                                            ->helperText('Contoh: MIDTRANS,QRIS,BCA / MANDIRI,GOPAY / OVO')
                                            ->maxLength(255),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $settingTypes = [
            'maintenance_mode' => 'boolean',
            'announcement_active' => 'boolean',
            'footer_trust_pillars' => 'json',
            'site_logo' => 'image',
            'site_favicon' => 'image',
            'announcement_bg_color' => 'color',
            'announcement_text_color' => 'color',
        ];

        foreach ($data as $key => $value) {
            $type = $settingTypes[$key] ?? 'text';

            if ($type === 'json' && is_array($value)) {
                $savedValue = json_encode($value);
            } elseif (is_bool($value)) {
                $savedValue = $value ? '1' : '0';
            } else {
                $savedValue = $value !== null ? (string) $value : '';
            }

            CmsSetting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $savedValue,
                    'type' => $type,
                    'group' => $this->getGroupForKey($key),
                ]
            );
        }

        CmsSetting::clearCache();
        CmsCache::bump();

        Notification::make()
            ->title('Pengaturan Berhasil Disimpan!')
            ->body('Semua perubahan pengaturan toko telah langsung aktif di website.')
            ->success()
            ->send();
    }

    protected function getGroupForKey(string $key): string
    {
        return match (true) {
            str_starts_with($key, 'site_'), str_starts_with($key, 'copyright'), str_starts_with($key, 'powered_by'), str_starts_with($key, 'maintenance') => 'general',
            str_starts_with($key, 'primary_'), str_starts_with($key, 'accent_'), str_starts_with($key, 'font_') => 'branding',
            str_starts_with($key, 'announcement_') => 'announcement',
            str_starts_with($key, 'social_') => 'social',
            str_starts_with($key, 'seo_'), str_starts_with($key, 'google_'), str_starts_with($key, 'facebook_'), str_starts_with($key, 'custom_head') => 'seo',
            str_starts_with($key, 'footer_') => 'footer',
            default => 'general',
        };
    }
}
