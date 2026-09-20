<div style="border-top: 1px solid var(--rc-border, #e2e8f0); margin-top: auto;">
    {{-- Expanded Mode: Full Rich Footer --}}
    <div x-show="$store.sidebar.isOpen" style="padding: 12px 16px 16px;">
        <a href="https://demo1-ecommerce.radiantcode.web.id" target="_blank" rel="noopener noreferrer" style="display: flex; align-items: center; justify-content: space-between; padding: 10px 14px; background: var(--rc-surface-2, #0f172a); color: var(--rc-text, #ffffff); border: 1px solid var(--rc-border, rgba(255,255,255,0.1)); border-radius: 10px; font-size: 0.8rem; font-weight: 600; text-decoration: none; box-shadow: var(--rc-shadow-sm); transition: all 0.2s ease;">
            <span style="display: flex; align-items: center; gap: 8px;">
                <span style="display: inline-block; width: 7px; height: 7px; background: var(--rc-emerald, #10b981); border-radius: 9999px; box-shadow: 0 0 6px var(--rc-emerald, #10b981);"></span>
                <span>Buka Web Toko</span>
            </span>
            <span style="font-size: 0.95rem; opacity: 0.75;">↗</span>
        </a>
        <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 10px; padding: 0 4px; font-size: 0.7rem; color: var(--rc-text-subtle, #94a3b8); font-weight: 500;">
            <span>Payment Gateway</span>
            <span style="background: var(--rc-surface-2, #f1f5f9); color: var(--rc-text-muted, #475569); border: 1px solid var(--rc-border, #e2e8f0); padding: 2px 7px; border-radius: 9999px; font-weight: 600; font-size: 0.65rem;">Midtrans Snap</span>
        </div>
    </div>

    {{-- Collapsed Mode: Clean Centered Mini Icon --}}
    <div x-show="! $store.sidebar.isOpen" style="padding: 12px 6px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
        <a href="https://localhost:5173" target="_blank" rel="noopener noreferrer" title="Buka Web Toko" style="display: flex; align-items: center; justify-content: center; width: 38px; height: 38px; background: var(--rc-surface-2); color: var(--rc-emerald); border: 1px solid var(--rc-border); border-radius: 10px; text-decoration: none; transition: all 0.2s ease;">
            <span style="display: inline-block; width: 8px; height: 8px; background: var(--rc-emerald); border-radius: 9999px; box-shadow: 0 0 6px var(--rc-emerald);"></span>
        </a>
    </div>
</div>
