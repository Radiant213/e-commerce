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
           onmouseover="this.style.background='rgba(99, 102, 241, 0.12)'; this.style.color='#4f46e5';"
           onmouseout="this.style.background='transparent'; this.style.color='inherit';">
            <span style="display: flex; align-items: center; gap: 9px;">
                <span style="display: flex; align-items: center; justify-content: center; width: 26px; height: 26px; border-radius: 7px; background: rgba(99, 102, 241, 0.18); color: #6366f1; flex-shrink: 0;">
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

        {{-- Buka Panel Admin --}}
        <a href="/admin" target="_self" 
           style="display: flex; align-items: center; justify-content: space-between; padding: 8px 10px; border-radius: 8px; font-size: 0.78rem; font-weight: 600; text-decoration: none; color: inherit; transition: all 0.15s ease;"
           onmouseover="this.style.background='rgba(16, 185, 129, 0.12)'; this.style.color='#059669';"
           onmouseout="this.style.background='transparent'; this.style.color='inherit';">
            <span style="display: flex; align-items: center; gap: 9px;">
                <span style="display: flex; align-items: center; justify-content: center; width: 26px; height: 26px; border-radius: 7px; background: rgba(16, 185, 129, 0.18); color: #10b981; flex-shrink: 0;">
                    <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </span>
                <span>Buka Panel Admin</span>
            </span>
            <span style="font-size: 0.75rem; opacity: 0.5;">⚙</span>
        </a>
        @endif
    </div>

    {{-- Collapsed Mode --}}
    <div x-show="! $store.sidebar.isOpen" style="padding: 4px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px;">
        <a href="{{ $frontendUrl }}" target="_self" title="Kembali ke Toko" style="display: flex; align-items: center; justify-content: center; width: 36px; height: 36px; background: rgba(99, 102, 241, 0.18); color: #6366f1; border-radius: 9px; text-decoration: none;">
            <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
        </a>
    </div>
</div>
