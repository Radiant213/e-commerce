<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    /* =============================================
       RADIANTCOMMERCE ADMIN — LUXURY DESIGN SYSTEM
       No Native Browser Controls • Full Animations
       Light Mode & Deep Dark Mode Supported
    ============================================= */

    [x-cloak] {
        display: none !important;
    }

    /* 1. Design Tokens */
    :root {
        --font-heading: 'Plus Jakarta Sans', -apple-system, sans-serif;
        --font-body: 'Inter', -apple-system, sans-serif;

        --rc-bg:           #F8FAFC;
        --rc-surface:      #FFFFFF;
        --rc-surface-2:    #F1F5F9;
        --rc-border:       #E2E8F0;
        --rc-border-2:     #CBD5E1;
        --rc-text:         #0F172A;
        --rc-text-muted:   #64748B;
        --rc-text-subtle:  #94A3B8;
        --rc-emerald:      #10B981;
        --rc-emerald-hover:#059669;
        --rc-emerald-dim:  rgba(16, 185, 129, 0.12);
        --rc-row-hover:    #F8FAFC;
        --rc-shadow-sm:    0 1px 3px rgba(15, 23, 42, 0.04);
        --rc-shadow-md:    0 8px 24px -4px rgba(15, 23, 42, 0.08);
        --rc-shadow-pop:   0 16px 32px -8px rgba(15, 23, 42, 0.14);
    }

    html.dark {
        --rc-bg:           #080C14;
        --rc-surface:      #0F1724;
        --rc-surface-2:    #162132;
        --rc-border:       #1E2D42;
        --rc-border-2:     #2B3E58;
        --rc-text:         #E2E8F0;
        --rc-text-muted:   #94A3B8;
        --rc-text-subtle:  #64748B;
        --rc-emerald:      #34D399;
        --rc-emerald-hover:#10B981;
        --rc-emerald-dim:  rgba(52, 211, 153, 0.12);
        --rc-row-hover:    #141E2E;
        --rc-shadow-sm:    0 1px 4px rgba(0, 0, 0, 0.4);
        --rc-shadow-md:    0 10px 28px -4px rgba(0, 0, 0, 0.5);
        --rc-shadow-pop:   0 20px 40px -8px rgba(0, 0, 0, 0.7);
    }

    /* 2. Global Typography */
    body, .fi-sidebar, .fi-topbar, .fi-ta, .fi-form,
    input, select, textarea, button {
        font-family: var(--font-body) !important;
        -webkit-font-smoothing: antialiased;
    }

    h1, h2, h3, h4, h5, h6,
    .fi-header-heading,
    .fi-ta-header-heading,
    .fi-section-header-heading,
    .fi-wi-stats-overview-stat-value,
    .fi-modal-heading {
        font-family: var(--font-heading) !important;
        letter-spacing: -0.025em !important;
        font-weight: 800 !important;
    }

    /* 3. Global Canvas */
    html.dark body,
    .fi-layout, .fi-main {
        background-color: var(--rc-bg) !important;
    }

    /* =============================================
       NO NATIVE BROWSER CONTROLS (FULL CUSTOM STYLING)
    ============================================= */

    /* 4. Number Input: Remove ugly browser spin buttons */
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none !important;
        margin: 0 !important;
        display: none !important;
    }
    input[type=number] {
        -moz-appearance: textfield !important;
        appearance: textfield !important;
    }

    /* 5. Custom Select & Dropdown Styling (Tables, Pagination, Native fallbacks) */
    select:not(.fi-select-input),
    select.fi-select-input,
    .fi-ta-pagination select,
    .fi-pagination select {
        -webkit-appearance: none !important;
        -moz-appearance: none !important;
        appearance: none !important;
        background-color: var(--rc-surface-2) !important;
        color: var(--rc-text) !important;
        border: 1px solid var(--rc-border) !important;
        border-radius: 10px !important;
        padding: 7px 32px 7px 12px !important;
        font-family: var(--font-body) !important;
        font-size: 0.85rem !important;
        font-weight: 500 !important;
        cursor: pointer !important;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2.2' stroke='%2310B981'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='m19.5 8.25-7.5 7.5-7.5-7.5'/%3E%3C/svg%3E") !important;
        background-repeat: no-repeat !important;
        background-position: right 12px center !important;
        background-size: 13px 13px !important;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
    }

    html.dark select:not(.fi-select-input),
    html.dark select.fi-select-input,
    html.dark .fi-ta-pagination select,
    html.dark .fi-pagination select {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2.2' stroke='%2334D399'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='m19.5 8.25-7.5 7.5-7.5-7.5'/%3E%3C/svg%3E") !important;
    }

    /* Filament Custom Select (native: false) Form Components */
    div.fi-select-input {
        background: transparent !important;
        background-image: none !important;
        border: none !important;
        padding: 0 !important;
        margin: 0 !important;
        box-shadow: none !important;
        width: 100% !important;
        position: relative !important;
    }

    .fi-select-input-btn,
    .fi-select-input .fi-select-input-btn {
        background-color: transparent !important;
        border: none !important;
        color: var(--rc-text) !important;
        font-family: var(--font-body) !important;
        font-size: 0.875rem !important;
        font-weight: 500 !important;
        padding: 8px 48px 8px 12px !important;
        width: 100% !important;
        cursor: pointer !important;
        background: transparent url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2.2' stroke='%2310B981'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='m19.5 8.25-7.5 7.5-7.5-7.5'/%3E%3C/svg%3E") no-repeat right 12px center / 13px 13px !important;
    }

    html.dark .fi-select-input-btn,
    html.dark .fi-select-input .fi-select-input-btn {
        background: transparent url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2.2' stroke='%2334D399'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='m19.5 8.25-7.5 7.5-7.5-7.5'/%3E%3C/svg%3E") no-repeat right 12px center / 13px 13px !important;
    }

    /* Custom dropdown clear (X) button — clean, uncircled, directly beside the arrow */
    .fi-select-input-value-remove-btn,
    .fi-select-input .fi-select-input-value-remove-btn {
        position: absolute !important;
        right: 34px !important;
        left: auto !important;
        top: calc(50% + 8.1px) !important;
        transform: translateY(-50%) !important;
        width: 16px !important;
        height: 16px !important;
        min-width: 16px !important;
        min-height: 16px !important;
        background: transparent !important;
        background-color: transparent !important;
        border: none !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        color: var(--rc-text-muted) !important;
        cursor: pointer !important;
        z-index: 5 !important;
        padding: 0 !important;
        margin: 0 !important;
        transition: all 0.15s ease !important;
    }

    .fi-select-input-value-remove-btn:hover,
    .fi-select-input .fi-select-input-value-remove-btn:hover {
        color: #EF4444 !important;
        transform: translateY(-50%) scale(1.2) !important;
        background: transparent !important;
        background-color: transparent !important;
    }

    .fi-select-input-value-remove-btn svg {
        width: 13px !important;
        height: 13px !important;
        min-width: 13px !important;
        min-height: 13px !important;
        display: block !important;
        stroke: currentColor !important;
        fill: currentColor !important;
    }

    select:hover,
    .fi-ta-pagination select:hover {
        border-color: var(--rc-emerald) !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px -2px var(--rc-emerald-dim) !important;
    }

    select:focus,
    .fi-ta-pagination select:focus {
        border-color: var(--rc-emerald) !important;
        box-shadow: 0 0 0 3px var(--rc-emerald-dim) !important;
        outline: none !important;
    }

    /* Style the Option items inside the dropdown (prevents OS light blue block) */
    select option {
        background-color: var(--rc-surface) !important;
        color: var(--rc-text) !important;
        padding: 10px 14px !important;
        font-size: 0.875rem !important;
        font-weight: 500 !important;
        border: none !important;
    }

    select option:checked,
    select option:hover {
        background-color: var(--rc-surface-2) !important;
        color: var(--rc-emerald) !important;
        font-weight: 600 !important;
    }

    /* 6. Filament Rich Custom Select (native: false) Dropdown Popover */
    .fi-dropdown-panel,
    .fi-select-panel,
    .fi-ac-dropdown-panel,
    .fi-popover-panel,
    [role="listbox"],
    div.fi-dropdown-panel {
        background-color: var(--rc-surface) !important;
        border: 1px solid var(--rc-border) !important;
        border-radius: 14px !important;
        box-shadow: var(--rc-shadow-pop), 0 20px 45px -10px rgba(0, 0, 0, 0.6) !important;
        overflow-y: auto !important;
        overflow-x: hidden !important;
        padding: 6px !important;
        z-index: 99999 !important;
        position: absolute !important;
    }

    html.dark .fi-dropdown-panel,
    html.dark .fi-select-panel,
    html.dark .fi-ac-dropdown-panel,
    html.dark .fi-popover-panel,
    html.dark [role="listbox"],
    html.dark div.fi-dropdown-panel {
        background-color: #0F1724 !important;
    }

    html:not(.dark) .fi-dropdown-panel,
    html:not(.dark) .fi-select-panel,
    html:not(.dark) .fi-ac-dropdown-panel,
    html:not(.dark) .fi-popover-panel,
    html:not(.dark) [role="listbox"],
    html:not(.dark) div.fi-dropdown-panel {
        background-color: #FFFFFF !important;
    }

    /* Base fields stacking context: low z-index */
    .fi-fo-field,
    [data-field-wrapper],
    .fi-fo-field-wrp {
        position: relative;
        z-index: 1;
    }

    /* Never clip dropdown popups or custom selects inside cards, sections, or grids */
    .fi-section,
    .fi-section-content-ctn,
    .fi-section-content,
    .fi-fo-field,
    [data-field-wrapper],
    .fi-fo-field-wrp,
    .fi-fo-field-wrp-item,
    .fi-grid,
    .fi-fo-select-wrp,
    .fi-select-input {
        overflow: visible !important;
    }

    /* Elevate active section or field wrapper so dropdowns float on top of ALL following fields, sections, and buttons */
    .fi-section:focus-within,
    .fi-section:has([aria-expanded="true"]),
    .fi-section:has([role="listbox"]:not([style*="display: none"])),
    .fi-fo-field:focus-within,
    .fi-fo-field:has([aria-expanded="true"]),
    .fi-fo-field:has([role="listbox"]:not([style*="display: none"])),
    [data-field-wrapper]:focus-within,
    [data-field-wrapper]:has([aria-expanded="true"]),
    [data-field-wrapper]:has([role="listbox"]:not([style*="display: none"])),
    .fi-fo-field-wrp:focus-within,
    .fi-fo-field-wrp:has([aria-expanded="true"]),
    .fi-fo-field-wrp:has([role="listbox"]:not([style*="display: none"])),
    .fi-fo-select-wrp:focus-within,
    .fi-fo-select-wrp:has([aria-expanded="true"]),
    .fi-fo-select-wrp:has([role="listbox"]:not([style*="display: none"])),
    .fi-input-wrp:focus-within,
    .fi-input-wrp:has([aria-expanded="true"]),
    .fi-input-wrp:has([role="listbox"]:not([style*="display: none"])),
    div.fi-select-input:focus-within,
    div.fi-select-input:has([aria-expanded="true"]),
    div.fi-select-input:has([role="listbox"]:not([style*="display: none"])),
    .fi-select-input-ctn:focus-within,
    .fi-select-input-ctn:has([aria-expanded="true"]),
    .fi-select-input-ctn:has([role="listbox"]:not([style*="display: none"])) {
        z-index: 9999 !important;
        position: relative !important;
    }

    /* Keep form bottom action buttons at lower z-index so dropdowns always float in front */
    .fi-form-actions,
    .fi-page-actions {
        position: relative !important;
        z-index: 10 !important;
    }

    .fi-dropdown-list-item,
    .fi-select-panel-option {
        border-radius: 9px !important;
        padding: 8px 12px !important;
        font-weight: 500 !important;
        font-size: 0.875rem !important;
        transition: all 0.15s ease !important;
        color: var(--rc-text) !important;
    }

    .fi-dropdown-list-item:hover,
    .fi-select-panel-option:hover {
        background-color: var(--rc-emerald-dim) !important;
        color: var(--rc-emerald) !important;
        transform: translateX(4px) !important;
    }

    .fi-select-panel-option[aria-selected="true"] {
        background-color: var(--rc-emerald-dim) !important;
        color: var(--rc-emerald) !important;
        font-weight: 700 !important;
    }

    /* 7. Premium Custom Scrollbars */
    * {
        scrollbar-width: thin;
        scrollbar-color: var(--rc-border-2) transparent;
    }

    ::-webkit-scrollbar {
        width: 7px;
        height: 7px;
    }

    ::-webkit-scrollbar-track {
        background: transparent;
    }

    ::-webkit-scrollbar-thumb {
        background-color: var(--rc-border-2);
        border-radius: 9999px;
        border: 2px solid transparent;
        background-clip: content-box;
        transition: background-color 0.2s ease;
    }

    ::-webkit-scrollbar-thumb:hover {
        background-color: var(--rc-emerald);
    }

    /* =============================================
       PAGE COMPONENTS
    ============================================= */

    /* 8. Sidebar & Collapsible Animation */
    #fi-main-sidebar, .fi-sidebar {
        background-color: var(--rc-surface) !important;
        border-right: 1px solid var(--rc-border) !important;
        box-shadow: 1px 0 6px rgba(0, 0, 0, 0.04) !important;
        transition: width 0.3s cubic-bezier(0.16, 1, 0.3, 1),
                    transform 0.3s cubic-bezier(0.16, 1, 0.3, 1),
                    background-color 0.2s ease,
                    border-color 0.2s ease !important;
        will-change: width, transform;
    }

    .fi-main-ctn {
        transition: margin 0.3s cubic-bezier(0.16, 1, 0.3, 1),
                    padding 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
    }

    .fi-sidebar-header {
        border-bottom: 1px solid var(--rc-border) !important;
        padding: 16px 20px !important;
        background-color: var(--rc-surface) !important;
        transition: padding 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
    }

    .fi-sidebar-nav {
        padding: 12px 14px !important;
        transition: padding 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
    }

    .fi-sidebar-group-label, .fi-sidebar-group-btn {
        font-size: 0.68rem !important;
        font-weight: 800 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.08em !important;
        color: var(--rc-text-subtle) !important;
        padding-top: 14px !important;
        padding-bottom: 6px !important;
        transition: opacity 0.2s ease !important;
    }

    .fi-sidebar-item > .fi-sidebar-item-btn {
        border-radius: 10px !important;
        padding: 9px 12px !important;
        font-weight: 500 !important;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
    }

    .fi-sidebar-item:not(.fi-active) > .fi-sidebar-item-btn {
        color: var(--rc-text-muted) !important;
    }

    .fi-sidebar-item:not(.fi-active) > .fi-sidebar-item-btn:hover {
        background-color: var(--rc-surface-2) !important;
        color: var(--rc-text) !important;
        transform: translateX(4px) !important;
    }

    .fi-sidebar-item:not(.fi-active) > .fi-sidebar-item-btn .fi-sidebar-item-icon {
        color: var(--rc-text-muted) !important;
        transition: color 0.15s ease !important;
    }

    .fi-sidebar-item:not(.fi-active) > .fi-sidebar-item-btn:hover .fi-sidebar-item-icon {
        color: var(--rc-emerald) !important;
    }

    /* Active Sidebar Item with glowing accent bar (in open mode) */
    .fi-sidebar-item.fi-active > .fi-sidebar-item-btn {
        background-color: var(--rc-emerald-dim) !important;
        color: var(--rc-emerald) !important;
        font-weight: 600 !important;
        border: 1px solid rgba(52, 211, 153, 0.25) !important;
        position: relative !important;
    }

    .fi-sidebar-item.fi-active > .fi-sidebar-item-btn::before {
        content: '';
        position: absolute;
        left: 0;
        top: 20%;
        bottom: 20%;
        width: 3.5px;
        border-radius: 0 4px 4px 0;
        background-color: var(--rc-emerald);
        box-shadow: 0 0 10px var(--rc-emerald);
    }

    .fi-sidebar-item.fi-active > .fi-sidebar-item-btn .fi-sidebar-item-icon,
    .fi-sidebar-item.fi-active > .fi-sidebar-item-btn .fi-sidebar-item-label {
        color: var(--rc-emerald) !important;
    }

    /* Collapsed (Icon-Only) Sidebar Mode */
    #fi-main-sidebar:not(.fi-sidebar-open) {
        width: 4.75rem !important;
    }

    #fi-main-sidebar:not(.fi-sidebar-open) .fi-sidebar-nav {
        padding: 12px 6px !important;
    }

    #fi-main-sidebar:not(.fi-sidebar-open) .fi-sidebar-header {
        padding: 16px 8px !important;
        display: flex !important;
        justify-content: center !important;
    }

    #fi-main-sidebar:not(.fi-sidebar-open) .fi-sidebar-item-btn {
        justify-content: center !important;
        padding: 9px !important;
        margin: 0 auto 4px auto !important;
        width: 40px !important;
        height: 40px !important;
        display: flex !important;
        align-items: center !important;
    }

    #fi-main-sidebar:not(.fi-sidebar-open) .fi-sidebar-item:not(.fi-active) > .fi-sidebar-item-btn:hover {
        transform: scale(1.1) !important;
        background-color: var(--rc-surface-2) !important;
    }

    #fi-main-sidebar:not(.fi-sidebar-open) .fi-sidebar-item.fi-active > .fi-sidebar-item-btn::before {
        display: none !important;
    }

    #fi-main-sidebar:not(.fi-sidebar-open) .fi-sidebar-item.fi-active > .fi-sidebar-item-btn {
        box-shadow: 0 0 12px var(--rc-emerald-dim) !important;
    }

    #fi-main-sidebar:not(.fi-sidebar-open) .fi-sidebar-item-icon {
        margin: 0 !important;
    }

    /* Collapse/Expand Toggle Buttons */
    .fi-sidebar-close-collapse-sidebar-btn,
    .fi-sidebar-open-collapse-sidebar-btn {
        transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), background-color 0.2s ease !important;
        border-radius: 8px !important;
    }
    .fi-sidebar-close-collapse-sidebar-btn:hover,
    .fi-sidebar-open-collapse-sidebar-btn:hover {
        transform: scale(1.1) !important;
        background-color: var(--rc-surface-2) !important;
    }

    /* 9. Topbar */
    .fi-topbar, .fi-topbar-ctn {
        background-color: var(--rc-surface) !important;
        border-bottom: 1px solid var(--rc-border) !important;
        box-shadow: var(--rc-shadow-sm) !important;
    }

    /* 10. Cards, Sections & Widgets (Excluding Stats Overview) */
    .fi-section:not(:has(.fi-wi-stats-overview-stat)),
    .fi-wi:not(:has(.fi-wi-stats-overview-stat)),
    .fi-ta-ctn {
        border-radius: 14px !important;
        border: 1px solid var(--rc-border) !important;
        background-color: var(--rc-surface) !important;
        box-shadow: var(--rc-shadow-sm) !important;
        transition: border-color 0.2s ease, box-shadow 0.2s ease !important;
    }

    /* Completely Remove Outer Container Behind Stats Cards */
    .fi-wi-stats-overview,
    .fi-wi:has(.fi-wi-stats-overview-stat),
    .fi-section:has(.fi-wi-stats-overview-stat),
    .fi-wi-stats-overview .fi-section,
    .fi-wi-stats-overview .fi-section-content-ctn,
    .fi-wi:has(.fi-wi-stats-overview),
    .fi-wi-stats-overview > div {
        background: transparent !important;
        background-color: transparent !important;
        border: none !important;
        box-shadow: none !important;
        padding: 0 !important;
    }

    .fi-section-header {
        border-bottom: 1px solid var(--rc-border) !important;
        padding-bottom: 14px !important;
    }

    /* 11. Buttons with spring physics */
    .fi-btn {
        border-radius: 10px !important;
        font-family: var(--font-heading) !important;
        font-weight: 600 !important;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
    }

    .fi-btn-primary {
        background-color: var(--rc-emerald) !important;
        color: #FFFFFF !important;
        box-shadow: 0 2px 6px -1px var(--rc-emerald-dim) !important;
    }

    .fi-btn-primary:hover {
        filter: brightness(1.08) !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 6px 16px -2px var(--rc-emerald-dim) !important;
    }

    .fi-btn-primary:active {
        transform: translateY(0) scale(0.97) !important;
    }

    /* 12. Form Inputs */
    .fi-input-wrp {
        background-color: var(--rc-surface-2) !important;
        border: 1px solid var(--rc-border) !important;
        border-radius: 10px !important;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
    }

    .fi-input-wrp input,
    .fi-input-wrp textarea {
        background-color: transparent !important;
        color: var(--rc-text) !important;
    }

    .fi-input-wrp:hover {
        border-color: var(--rc-border-2) !important;
    }

    .fi-input-wrp:focus-within {
        border-color: var(--rc-emerald) !important;
        box-shadow: 0 0 0 3px var(--rc-emerald-dim) !important;
        transform: translateY(-1px) !important;
    }

    .fi-fo-field-wrp-label, label {
        color: var(--rc-text-muted) !important;
        font-weight: 500 !important;
    }

    .fi-fo-helper-text { color: var(--rc-text-subtle) !important; }

    /* 13. Tables */
    .fi-ta-header-cell {
        background-color: var(--rc-surface-2) !important;
        color: var(--rc-text-subtle) !important;
        font-size: 0.72rem !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
        font-weight: 600 !important;
        border-bottom: 1px solid var(--rc-border) !important;
    }

    .fi-ta-record {
        background-color: var(--rc-surface) !important;
        border-bottom: 1px solid var(--rc-border) !important;
        transition: background-color 0.18s ease !important;
    }

    .fi-ta-record:hover {
        background-color: var(--rc-row-hover) !important;
    }

    .fi-ta-cell, .fi-ta-text-item { color: var(--rc-text) !important; }

    /* Table Actions Column: Solid Background & Clean Alignment */
    .fi-ta-actions-cell,
    td.fi-ta-actions-cell,
    th.fi-ta-actions-header-cell {
        background-color: var(--rc-surface) !important;
        white-space: nowrap !important;
    }

    html.dark .fi-ta-actions-cell,
    html.dark td.fi-ta-actions-cell,
    html.dark th.fi-ta-actions-header-cell {
        background-color: #0F1724 !important;
    }

    html:not(.dark) .fi-ta-actions-cell,
    html:not(.dark) td.fi-ta-actions-cell,
    html:not(.dark) th.fi-ta-actions-header-cell {
        background-color: #FFFFFF !important;
    }

    .fi-ta-record:hover .fi-ta-actions-cell,
    .fi-ta-record:hover td.fi-ta-actions-cell {
        background-color: var(--rc-row-hover) !important;
    }

    /* 14. Clean Standalone Stat Cards (Smooth Modern Hover) */
    .fi-wi-stats-overview-stat {
        background-color: var(--rc-surface) !important;
        border: 1px solid var(--rc-border) !important;
        border-radius: 16px !important;
        box-shadow: var(--rc-shadow-sm) !important;
        overflow: hidden !important;
        position: relative !important;
        transition: transform 0.28s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow 0.28s ease, border-color 0.28s ease !important;
    }

    .fi-wi-stats-overview-stat:hover {
        transform: translateY(-3px) !important;
        border-color: var(--rc-border-2) !important;
        box-shadow: var(--rc-shadow-md), 0 8px 24px -4px rgba(15, 23, 42, 0.08) !important;
    }

    html.dark .fi-wi-stats-overview-stat:hover {
        border-color: rgba(52, 211, 153, 0.45) !important;
        box-shadow: var(--rc-shadow-md), 0 12px 28px -6px rgba(0, 0, 0, 0.6), 0 0 0 1px rgba(52, 211, 153, 0.15) !important;
    }

    .fi-wi-stats-overview-stat-label { color: var(--rc-text-muted) !important; }
    .fi-wi-stats-overview-stat-value { color: var(--rc-text) !important; }
    .fi-wi-stats-overview-stat-description { color: var(--rc-text-subtle) !important; }

    /* 15. Pagination Controls */
    .fi-pagination-item {
        border-radius: 8px !important;
        color: var(--rc-text-muted) !important;
        transition: all 0.18s ease !important;
    }

    .fi-pagination-item:hover {
        background-color: var(--rc-surface-2) !important;
        color: var(--rc-text) !important;
        transform: translateY(-1px) !important;
    }

    .fi-pagination-item[aria-current="page"] {
        background-color: var(--rc-emerald) !important;
        color: #FFFFFF !important;
        font-weight: 700 !important;
        box-shadow: 0 2px 8px -2px var(--rc-emerald-dim) !important;
    }

    /* 16. Badges */
    .fi-badge {
        border-radius: 9999px !important;
        font-weight: 600 !important;
        font-size: 0.75rem !important;
        transition: transform 0.18s cubic-bezier(0.16, 1, 0.3, 1) !important;
        display: inline-flex !important;
    }

    .fi-badge:hover {
        transform: scale(1.08) !important;
    }

    /* 17. Notification Toasts */
    .fi-no-notification {
        background-color: var(--rc-surface) !important;
        border: 1px solid var(--rc-border) !important;
        border-radius: 14px !important;
        color: var(--rc-text) !important;
        box-shadow: var(--rc-shadow-pop) !important;
    }

    /* =============================================
       LUXURY ANIMATIONS & TRANSITIONS
    ============================================= */
    @keyframes rcFadeIn {
        0% { opacity: 0; }
        100% { opacity: 1; }
    }

    @keyframes rcFadeInUp {
        0% {
            opacity: 0;
            transform: translateY(12px);
        }
        100% {
            opacity: 1;
            transform: none; /* explicitly reset transform to none to prevent containing block trap */
        }
    }

    @keyframes rcDropdownPop {
        0% {
            opacity: 0;
            transform: translateY(-8px) scale(0.97);
        }
        100% {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    @keyframes rcModalZoom {
        0% {
            opacity: 0;
            transform: translateY(16px) scale(0.96);
        }
        100% {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    /* Entry animations - fi-main uses clean opacity fade to guarantee fixed modals are not trapped */
    .fi-main {
        animation: rcFadeIn 0.25s ease both;
    }

    .fi-section,
    .fi-ta-ctn,
    .fi-wi {
        animation: rcFadeIn 0.25s ease both;
    }

    /* Staggered overview widgets */
    .fi-wi-stats-overview-stat {
        animation: rcFadeInUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) both;
    }
    .fi-wi-stats-overview-stat:nth-child(1) { animation-delay: 0.05s; }
    .fi-wi-stats-overview-stat:nth-child(2) { animation-delay: 0.10s; }
    .fi-wi-stats-overview-stat:nth-child(3) { animation-delay: 0.15s; }
    .fi-wi-stats-overview-stat:nth-child(4) { animation-delay: 0.20s; }

    /* Dropdown pop animation */
    .fi-dropdown-panel,
    .fi-select-panel,
    .fi-ac-dropdown-panel {
        animation: rcDropdownPop 0.18s cubic-bezier(0.16, 1, 0.3, 1) !important;
        transform-origin: top !important;
    }

    /* Notification slide in */
    @keyframes rcToastSlide {
        0% {
            opacity: 0;
            transform: translateX(30px) scale(0.95);
        }
        100% {
            opacity: 1;
            transform: translateX(0) scale(1);
        }
    }
    .fi-no-notification {
        animation: rcToastSlide 0.28s cubic-bezier(0.16, 1, 0.3, 1) !important;
    }

    /* =============================================
       18. Centered Full-Screen Modals & Action Alerts
       Fixes containing block offset so modals are centered in entire viewport
    ============================================= */
    .fi-modal {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        z-index: 99999 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    .fi-modal-close-overlay {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        background-color: rgba(4, 7, 13, 0.76) !important;
        backdrop-filter: blur(10px) !important;
        -webkit-backdrop-filter: blur(10px) !important;
        z-index: 99998 !important;
    }

    .fi-modal-window-ctn {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 1.5rem !important;
        z-index: 99999 !important;
        pointer-events: none !important;
    }

    .fi-modal-window {
        pointer-events: auto !important;
        margin: auto !important;
        max-width: 26rem !important;
        width: 100% !important;
        background-color: var(--rc-surface) !important;
        border: 1px solid var(--rc-border) !important;
        border-radius: 20px !important;
        box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.8), 0 0 0 1px rgba(255, 255, 255, 0.05) !important;
        overflow: hidden !important;
        animation: rcModalZoom 0.24s cubic-bezier(0.16, 1, 0.3, 1) !important;
        text-align: center !important;
        position: relative !important;
    }

    /* Modal Close 'X' Button in Top Right Corner */
    .fi-modal-close-btn {
        position: absolute !important;
        top: 14px !important;
        right: 14px !important;
        width: 32px !important;
        height: 32px !important;
        border-radius: 8px !important;
        background: transparent !important;
        border: 1px solid transparent !important;
        color: var(--rc-text-muted) !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        cursor: pointer !important;
        transition: all 0.18s ease !important;
        z-index: 10 !important;
    }

    .fi-modal-close-btn:hover {
        background-color: var(--rc-surface-2) !important;
        border-color: var(--rc-border) !important;
        color: var(--rc-text) !important;
    }

    .fi-modal-close-btn svg {
        width: 16px !important;
        height: 16px !important;
    }

    /* Modal Icon & Alert Polish */
    .fi-modal-header {
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        text-align: center !important;
        padding: 28px 24px 12px 24px !important;
        border-bottom: none !important;
    }

    .fi-modal-header .fi-modal-icon-bg {
        width: 56px !important;
        height: 56px !important;
        border-radius: 16px !important;
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.18), rgba(16, 185, 129, 0.05)) !important;
        border: 1px solid rgba(16, 185, 129, 0.3) !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        margin: 0 auto 14px auto !important;
        box-shadow: 0 4px 16px -2px rgba(16, 185, 129, 0.25) !important;
    }

    .fi-modal-header .fi-modal-icon-bg svg {
        width: 28px !important;
        height: 28px !important;
        color: var(--rc-emerald) !important;
    }

    .fi-modal-heading {
        font-family: var(--font-heading) !important;
        font-size: 1.25rem !important;
        font-weight: 800 !important;
        letter-spacing: -0.02em !important;
        color: var(--rc-text) !important;
    }

    .fi-modal-description {
        font-size: 0.875rem !important;
        color: var(--rc-text-muted) !important;
        line-height: 1.55 !important;
        margin-top: 6px !important;
        text-align: center !important;
        padding: 0 12px !important;
    }

    /* Modal Footer Container */
    .fi-modal-footer {
        padding: 16px 24px 24px 24px !important;
        width: 100% !important;
        display: block !important;
        border-top: none !important;
        box-sizing: border-box !important;
    }

    /* Modal Footer Actions Grid — 1fr 1fr Side-by-Side Symmetrical Buttons */
    .fi-modal-footer .fi-modal-footer-actions,
    .fi-modal-footer-actions {
        display: grid !important;
        grid-template-columns: 1fr 1fr !important;
        gap: 12px !important;
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        box-sizing: border-box !important;
        position: static !important;
    }

    .fi-modal-footer-actions > * {
        width: 100% !important;
        min-width: 0 !important;
        margin: 0 !important;
        position: static !important;
    }

    .fi-modal-footer-actions .fi-btn,
    .fi-modal-footer-actions button {
        width: 100% !important;
        min-width: 0 !important;
        height: 44px !important;
        min-height: 44px !important;
        padding: 10px 16px !important;
        border-radius: 12px !important;
        font-size: 0.875rem !important;
        font-weight: 700 !important;
        white-space: nowrap !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        line-height: 1 !important;
        margin: 0 !important;
        box-sizing: border-box !important;
        flex: none !important;
        position: static !important;
        transform: none !important;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
    }

    /* Cancel Button (Batal) */
    .fi-modal-footer-actions .fi-btn-color-gray,
    .fi-modal-footer-actions .fi-btn-secondary,
    .fi-modal-footer-actions button[x-on\:click*="close"],
    .fi-modal-footer-actions > :first-child .fi-btn,
    .fi-modal-footer-actions > :first-child button {
        background-color: var(--rc-surface-2) !important;
        color: var(--rc-text-muted) !important;
        border: 1px solid var(--rc-border) !important;
        box-shadow: none !important;
    }

    .fi-modal-footer-actions .fi-btn-color-gray:hover,
    .fi-modal-footer-actions .fi-btn-secondary:hover,
    .fi-modal-footer-actions button[x-on\:click*="close"]:hover,
    .fi-modal-footer-actions > :first-child .fi-btn:hover,
    .fi-modal-footer-actions > :first-child button:hover {
        background-color: var(--rc-border) !important;
        color: var(--rc-text) !important;
        border-color: var(--rc-border-2) !important;
    }

    /* Confirm Button (Konfirmasi) */
    .fi-modal-footer-actions .fi-btn-primary,
    .fi-modal-footer-actions .fi-btn-color-primary,
    .fi-modal-footer-actions button[type="submit"],
    .fi-modal-footer-actions > :last-child .fi-btn,
    .fi-modal-footer-actions > :last-child button {
        background: linear-gradient(135deg, var(--rc-emerald), var(--rc-emerald-hover)) !important;
        color: #FFFFFF !important;
        border: 1px solid rgba(255, 255, 255, 0.18) !important;
        box-shadow: 0 4px 14px -2px rgba(16, 185, 129, 0.5) !important;
    }

    .fi-modal-footer-actions .fi-btn-primary:hover,
    .fi-modal-footer-actions .fi-btn-color-primary:hover,
    .fi-modal-footer-actions button[type="submit"]:hover,
    .fi-modal-footer-actions > :last-child .fi-btn:hover,
    .fi-modal-footer-actions > :last-child button:hover {
        filter: brightness(1.08) !important;
        box-shadow: 0 6px 20px -2px rgba(16, 185, 129, 0.65) !important;
    }

    /* =============================================
       19. Custom Pagination Records-Per-Page Dropdown
    ============================================= */
    .rc-page-select-wrapper {
        position: relative !important;
        display: inline-block !important;
    }

    .rc-page-select-btn {
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
        padding: 6px 12px !important;
        background-color: var(--rc-surface-2) !important;
        border: 1px solid var(--rc-border) !important;
        border-radius: 10px !important;
        font-size: 0.8rem !important;
        font-weight: 500 !important;
        color: var(--rc-text) !important;
        cursor: pointer !important;
        line-height: 1.4 !important;
        transition: all 0.2s ease !important;
    }

    .rc-page-select-btn:hover {
        border-color: var(--rc-emerald) !important;
        box-shadow: 0 0 0 2px var(--rc-emerald-dim) !important;
    }

    .rc-page-badge {
        background-color: var(--rc-emerald-dim) !important;
        color: var(--rc-emerald) !important;
        font-weight: 700 !important;
        padding: 2px 7px !important;
        border-radius: 6px !important;
        font-size: 0.75rem !important;
        line-height: 1 !important;
    }

    .rc-page-arrow {
        width: 14px !important;
        height: 14px !important;
        min-width: 14px !important;
        max-width: 14px !important;
        stroke: var(--rc-emerald) !important;
        stroke-width: 2.2 !important;
        transition: transform 0.2s ease !important;
        display: inline-block !important;
    }

    .rc-page-dropdown-panel {
        position: absolute !important;
        bottom: calc(100% + 8px) !important;
        right: 0 !important;
        min-width: 140px !important;
        background-color: var(--rc-surface) !important;
        border: 1px solid var(--rc-border) !important;
        border-radius: 14px !important;
        box-shadow: var(--rc-shadow-pop) !important;
        backdrop-filter: blur(16px) !important;
        -webkit-backdrop-filter: blur(16px) !important;
        padding: 6px !important;
        z-index: 9999 !important;
        display: flex;
        flex-direction: column;
        gap: 3px;
    }

    .rc-page-dropdown-panel[style*="display: none"],
    .rc-page-dropdown-panel[style*="display:none"],
    [x-cloak].rc-page-dropdown-panel,
    .rc-page-dropdown-panel[x-cloak] {
        display: none !important;
    }

    .rc-page-dropdown-header {
        padding: 6px 8px 4px 8px !important;
        font-size: 0.68rem !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.06em !important;
        color: var(--rc-text-subtle) !important;
    }

    .rc-page-dropdown-option {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        width: 100% !important;
        padding: 7px 10px !important;
        font-size: 0.8rem !important;
        font-weight: 500 !important;
        color: var(--rc-text) !important;
        border-radius: 8px !important;
        background: transparent !important;
        border: none !important;
        cursor: pointer !important;
        text-align: left !important;
        transition: all 0.15s ease !important;
    }

    .rc-page-dropdown-option:hover {
        background-color: var(--rc-surface-2) !important;
        color: var(--rc-text) !important;
        transform: translateX(3px) !important;
    }

    .rc-page-dropdown-option[data-active="true"] {
        background-color: var(--rc-emerald-dim) !important;
        color: var(--rc-emerald) !important;
        font-weight: 700 !important;
    }

    .rc-page-check {
        width: 14px !important;
        height: 14px !important;
        min-width: 14px !important;
        max-width: 14px !important;
        stroke: var(--rc-emerald) !important;
        stroke-width: 2.5 !important;
        display: inline-block;
    }

    /* 20. Reports Page & Blade Icon Global Safety Constraints */
    .fi-page svg,
    .rc-report-wrapper svg,
    .fi-main button svg,
    .fi-main a svg {
        max-width: 100%;
    }
    .rc-report-tab-btn svg,
    .rc-report-btn svg {
        width: 16px !important;
        height: 16px !important;
        min-width: 16px !important;
        max-width: 16px !important;
    }
    /* 21. Global Toggle Switches (Vibrant Emerald ON / Smooth Slate OFF) */
    [role="switch"][aria-checked="true"],
    button[role="switch"][aria-checked="true"],
    div[role="switch"][aria-checked="true"],
    .fi-toggle[aria-checked="true"],
    .fi-ta-toggle [role="switch"][aria-checked="true"],
    .fi-fo-toggle [role="switch"][aria-checked="true"],
    .fi-ta-toggle-btn[aria-checked="true"],
    .fi-fo-toggle-btn[aria-checked="true"],
    .fi-toggle-on {
        background-color: #10B981 !important;
        border-color: #059669 !important;
        box-shadow: 0 0 12px rgba(16, 185, 129, 0.45) !important;
    }

    html.dark [role="switch"][aria-checked="true"],
    html.dark button[role="switch"][aria-checked="true"],
    html.dark div[role="switch"][aria-checked="true"],
    html.dark .fi-toggle[aria-checked="true"],
    html.dark .fi-ta-toggle [role="switch"][aria-checked="true"],
    html.dark .fi-fo-toggle [role="switch"][aria-checked="true"],
    html.dark .fi-ta-toggle-btn[aria-checked="true"],
    html.dark .fi-fo-toggle-btn[aria-checked="true"],
    html.dark .fi-toggle-on {
        background-color: #10B981 !important;
        border-color: #34D399 !important;
        box-shadow: 0 0 14px rgba(16, 185, 129, 0.55) !important;
    }

    [role="switch"][aria-checked="false"],
    button[role="switch"][aria-checked="false"],
    div[role="switch"][aria-checked="false"],
    .fi-toggle[aria-checked="false"],
    .fi-ta-toggle [role="switch"][aria-checked="false"],
    .fi-fo-toggle [role="switch"][aria-checked="false"],
    .fi-ta-toggle-btn[aria-checked="false"],
    .fi-fo-toggle-btn[aria-checked="false"] {
        background-color: #CBD5E1 !important;
        border-color: #94A3B8 !important;
        box-shadow: none !important;
    }

    html.dark [role="switch"][aria-checked="false"],
    html.dark button[role="switch"][aria-checked="false"],
    html.dark div[role="switch"][aria-checked="false"],
    html.dark .fi-toggle[aria-checked="false"],
    html.dark .fi-ta-toggle [role="switch"][aria-checked="false"],
    html.dark .fi-fo-toggle [role="switch"][aria-checked="false"],
    html.dark .fi-ta-toggle-btn[aria-checked="false"],
    html.dark .fi-fo-toggle-btn[aria-checked="false"] {
        background-color: #334155 !important;
        border-color: #475569 !important;
        box-shadow: none !important;
    }

    [role="switch"] > div,
    [role="switch"] > span {
        background-color: #FFFFFF !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.25) !important;
        transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), background-color 0.2s ease !important;
    }
</style>


