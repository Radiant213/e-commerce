@php
    $frontendUrl = config('app.frontend_url', '/');
    $isAdmin = auth()->user()?->role === 'admin';
@endphp
<div style="border-top: 1px solid var(--rc-border, #e2e8f0); margin-top: auto;">
    {{-- Expanded Mode --}}
    <div x-show="$store.sidebar.isOpen" style="padding: 12px 14px 14px; display: flex; flex-direction: column; gap: 8px;">
        <a href="{{ $frontendUrl }}" style="display: flex; align-items: center; justify-content: space-between; padding: 9px 12px; background: #0f172a; color: #ffffff; border-radius: 9px; font-size: 0.8rem; font-weight: 600; text-decoration: none; transition: background 0.15s ease;">
            <span style="display: flex; align-items: center; gap: 8px;">
                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Kembali ke Toko</span>
            </span>
            <span style="font-size: 0.85rem; opacity: 0.8;">←</span>
        </a>

        @if($isAdmin)
        <a href="/cms" style="display: flex; align-items: center; justify-content: space-between; padding: 8px 12px; background: #eef2ff; color: #4338ca; border: 1px solid #c7d2fe; border-radius: 9px; font-size: 0.75rem; font-weight: 600; text-decoration: none; transition: all 0.15s ease;">
            <span style="display: flex; align-items: center; gap: 6px;">
                <span style="display: inline-block; width: 6px; height: 6px; background: #6366f1; border-radius: 9999px;"></span>
                <span>Buka Studio Konten (CMS)</span>
            </span>
            <span style="font-size: 0.75rem; opacity: 0.7;">✎</span>
        </a>
        @endif
    </div>

    {{-- Collapsed Mode --}}
    <div x-show="! $store.sidebar.isOpen" style="padding: 10px 6px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 6px;">
        <a href="{{ $frontendUrl }}" title="Kembali ke Toko" style="display: flex; align-items: center; justify-content: center; width: 36px; height: 36px; background: #0f172a; color: #ffffff; border-radius: 8px; text-decoration: none;">
            <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
        </a>
    </div>
</div>
