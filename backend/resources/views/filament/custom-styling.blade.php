<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    /* 1. Global Typography */
    :root {
        --font-heading: 'Plus Jakarta Sans', -apple-system, sans-serif;
        --font-body: 'Inter', -apple-system, sans-serif;
    }

    body,
    .fi-sidebar,
    .fi-topbar,
    .fi-ta,
    .fi-form,
    input,
    select,
    textarea {
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

    /* 2. Global Canvas & Main Background */
    .fi-layout,
    .fi-main {
        background-color: #FAFAFA !important;
    }

    /* 3. Sidebar Overhaul */
    #fi-main-sidebar,
    .fi-sidebar {
        background-color: #FFFFFF !important;
        border-right: 1px solid #E2E8F0 !important;
        box-shadow: 1px 0 3px rgba(15, 23, 42, 0.02) !important;
    }

    .fi-sidebar-header {
        border-bottom: 1px solid #E2E8F0 !important;
        padding: 16px 20px !important;
        background-color: #FFFFFF !important;
    }

    .fi-sidebar-nav {
        padding: 12px 14px !important;
    }

    /* Sidebar Group Label */
    .fi-sidebar-group-label,
    .fi-sidebar-group-btn {
        font-family: var(--font-heading) !important;
        font-size: 0.68rem !important;
        font-weight: 800 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.08em !important;
        color: #94A3B8 !important;
        padding-top: 14px !important;
        padding-bottom: 6px !important;
    }

    /* Sidebar Inactive Item */
    .fi-sidebar-item:not(.fi-active) > .fi-sidebar-item-btn {
        border-radius: 10px !important;
        color: #475569 !important;
        font-weight: 500 !important;
        padding: 9px 12px !important;
        transition: all 0.15s cubic-bezier(0.16, 1, 0.3, 1) !important;
    }

    .fi-sidebar-item:not(.fi-active) > .fi-sidebar-item-btn:hover {
        background-color: #F1F5F9 !important;
        color: #0F172A !important;
    }

    .fi-sidebar-item:not(.fi-active) > .fi-sidebar-item-btn .fi-sidebar-item-icon {
        color: #64748B !important;
        transition: color 0.15s ease !important;
    }

    .fi-sidebar-item:not(.fi-active) > .fi-sidebar-item-btn:hover .fi-sidebar-item-icon {
        color: #0F172A !important;
    }

    /* Sidebar Active Item (Sleek Slate 900 with Emerald Icon) */
    .fi-sidebar-item.fi-active > .fi-sidebar-item-btn {
        background-color: #0F172A !important;
        color: #FFFFFF !important;
        font-weight: 600 !important;
        border-radius: 10px !important;
        padding: 9px 12px !important;
        box-shadow: 0 4px 12px -2px rgba(15, 23, 42, 0.25) !important;
    }

    .fi-sidebar-item.fi-active > .fi-sidebar-item-btn .fi-sidebar-item-icon {
        color: #34D399 !important; /* Emerald glowing accent */
    }

    .fi-sidebar-item.fi-active > .fi-sidebar-item-btn .fi-sidebar-item-label {
        color: #FFFFFF !important;
    }

    /* Sidebar Badges */
    .fi-sidebar-item-badge {
        font-family: var(--font-heading) !important;
        font-weight: 700 !important;
        font-size: 0.7rem !important;
        border-radius: 9999px !important;
        padding: 2px 8px !important;
    }

    .fi-sidebar-item.fi-active .fi-sidebar-item-badge {
        background-color: #10B981 !important;
        color: #FFFFFF !important;
    }

    /* 4. Topbar Header */
    .fi-topbar {
        background-color: #FFFFFF !important;
        border-bottom: 1px solid #E2E8F0 !important;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.02) !important;
    }

    /* 5. Sleek Cards & Sections */
    .fi-section,
    .fi-wi,
    .fi-ta-ctn {
        border-radius: 14px !important;
        border: 1px solid #E2E8F0 !important;
        background-color: #FFFFFF !important;
        box-shadow: 0 1px 3px 0 rgba(15, 23, 42, 0.03), 0 1px 2px -1px rgba(15, 23, 42, 0.03) !important;
    }

    .fi-section-header {
        border-bottom: 1px solid #F1F5F9 !important;
        padding-bottom: 14px !important;
    }

    /* 6. Solid Luxury Buttons (Matching Website) */
    .fi-btn {
        border-radius: 10px !important;
        font-family: var(--font-heading) !important;
        font-weight: 600 !important;
        transition: all 0.15s cubic-bezier(0.16, 1, 0.3, 1) !important;
    }

    .fi-btn-primary {
        background-color: #0F172A !important;
        color: #FFFFFF !important;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
    }

    .fi-btn-primary:hover {
        background-color: #1E293B !important;
        transform: translateY(-1px) !important;
        box-shadow: 0 4px 8px -2px rgba(15, 23, 42, 0.2) !important;
    }

    /* 7. Inputs, Selects, & Textareas */
    .fi-input-wrp {
        border-radius: 10px !important;
        border: 1px solid #CBD5E1 !important;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.02) !important;
        transition: all 0.15s ease !important;
    }

    .fi-input-wrp:focus-within {
        border-color: #0F172A !important;
        box-shadow: 0 0 0 2px rgba(15, 23, 42, 0.08) !important;
    }

    /* 8. Table Headers & Hover */
    .fi-ta-header-cell {
        background-color: #F8FAFC !important;
        font-size: 0.72rem !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
        font-weight: 600 !important;
        color: #64748B !important;
        border-bottom: 1px solid #E2E8F0 !important;
    }

    .fi-ta-record:hover {
        background-color: #F8FAFC !important;
    }

    /* 9. Badges */
    .fi-badge {
        border-radius: 9999px !important;
        font-weight: 600 !important;
        font-size: 0.75rem !important;
    }
</style>
