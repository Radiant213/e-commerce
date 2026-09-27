<?php

namespace App\Models\Cms;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'subject',
        'subject_translations',
        'header_text',
        'header_text_translations',
        'body_content',
        'body_content_translations',
        'footer_text',
        'footer_text_translations',
        'is_active',
    ];

    protected $casts = [
        'subject_translations' => 'array',
        'header_text_translations' => 'array',
        'body_content_translations' => 'array',
        'footer_text_translations' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get a template by slug.
     */
    public static function getBySlug(string $slug, ?string $locale = null): ?array
    {
        $template = self::where('slug', $slug)->where('is_active', true)->first();

        if (!$template) {
            return null;
        }

        return [
            'subject' => ($locale && $template->subject_translations) ? ($template->subject_translations[$locale] ?? $template->subject) : $template->subject,
            'header_text' => ($locale && $template->header_text_translations) ? ($template->header_text_translations[$locale] ?? $template->header_text) : $template->header_text,
            'body_content' => ($locale && $template->body_content_translations) ? ($template->body_content_translations[$locale] ?? $template->body_content) : $template->body_content,
            'footer_text' => ($locale && $template->footer_text_translations) ? ($template->footer_text_translations[$locale] ?? $template->footer_text) : $template->footer_text,
        ];
    }
}
