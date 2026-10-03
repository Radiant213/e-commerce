@php
    $frontendUrl = config('app.frontend_url', '/');
    $isAdmin = auth()->user()?->role === 'admin';
@endphp
<div style="border-top: 1px solid rgba(148, 163, 184, 0.15); margin-top: auto; padding: 12px 14px 16px;">
    {{-- Expanded Mode: Unified Card Container --}}
    <div x-show="$store.sidebar.isOpen" style="background: rgba(148, 163, 184, 0.08); border: 1px solid rgba(148, 163, 184, 0.18); border-radius: 12px; padding: 6px; display: flex; flex-direction: column; gap: 4px; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">
        {{-- Kembali ke Toko --}}
        <a href="{{ $frontendUrl }}" target="_self" 
           style="display: flex; align-items: center; justify-content: space-between; padding: 8px 10px; border-radius: 8px; font-size: 0.78rem; font-weight: 600; text-decoration: none; color: inherit; transition: all 0.15s ease;"
           onmouseover="this.style.background='rgba(15, 23, 42, 0.12)'; this.style.color='#0f172a';"
           onmouseout="this.style.background='transparent'; this.style.color='inherit';">
            <span style="display: flex; align-items: center; gap: 9px;">
                <span style="display: flex; align-items: center; justify-content: center; width: 26px; height: 26px; border-radius: 7px; background: rgba(15, 23, 42, 0.15); color: #0f172a; flex-shrink: 0;">
                    <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </span>
                <span>Kembali ke Toko</span>
            </span>
            <span style="font-size: 0.75rem; opacity: 0.5;">←</span>
        </a>

        @if($isAdmin)
        <div style="height: 1px; background: rgba(148, 163, 184, 0.15); margin: 2px 4px;"></div>

        {{-- Buka Studio Konten (CMS) --}}
        <a href="/cms" target="_self" 
           style="display: flex; align-items: center; justify-content: space-between; padding: 8px 10px; border-radius: 8px; font-size: 0.78rem; font-weight: 600; text-decoration: none; color: inherit; transition: all 0.15s ease;"
           onmouseover="this.style.background='rgba(99, 102, 241, 0.12)'; this.style.color='#4f46e5';"
           onmouseout="this.style.background='transparent'; this.style.color='inherit';">
            <span style="display: flex; align-items: center; gap: 9px;">
                <span style="display: flex; align-items: center; justify-content: center; width: 26px; height: 26px; border-radius: 7px; background: rgba(99, 102, 241, 0.18); color: #6366f1; flex-shrink: 0;">
                    <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </span>
                <span>Buka Studio Konten (CMS)</span>
            </span>
            <span style="font-size: 0.75rem; opacity: 0.5;">✎</span>
        </a>
        @endif
    </div>

    {{-- Collapsed Mode --}}
    <div x-show="! $store.sidebar.isOpen" style="padding: 4px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px;">
        <a href="{{ $frontendUrl }}" target="_self" title="Kembali ke Toko" style="display: flex; align-items: center; justify-content: center; width: 36px; height: 36px; background: rgba(15, 23, 42, 0.15); color: #0f172a; border-radius: 9px; text-decoration: none;">
            <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
        </a>
    </div>
</div>
