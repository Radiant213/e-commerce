<x-filament-panels::page>
    <style>
        /* =========================================================
           RADIANTCOMMERCE REPORTS & ANALYTICS LUXURY DESIGN SYSTEM
           Self-contained CSS — No Tailwind compile step required
           Supports Light Mode & Deep Dark Mode with full physics
        ========================================================= */

        .rc-report-wrapper {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            font-family: var(--font-body, 'Inter', -apple-system, sans-serif);
            color: var(--rc-text, #0f172a);
        }

        /* Prevent any SVG icon from expanding to full viewport */
        .rc-report-wrapper svg {
            display: inline-block !important;
            vertical-align: middle !important;
            flex-shrink: 0 !important;
        }

        /* 1. Category Navigation Tabs */
        .rc-report-tabs {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.625rem;
            padding-bottom: 0.875rem;
            border-bottom: 1px solid var(--rc-border, #e2e8f0);
        }

        .rc-report-tab-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1.125rem;
            border-radius: 0.75rem;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            border: 1px solid var(--rc-border, #e2e8f0);
            background-color: var(--rc-surface, #ffffff);
            color: var(--rc-text-muted, #64748b);
            text-decoration: none;
            box-shadow: var(--rc-shadow-sm, 0 1px 3px rgba(15, 23, 42, 0.04));
        }

        .rc-report-tab-btn svg {
            width: 16px !important;
            height: 16px !important;
            color: inherit;
        }

        .rc-report-tab-btn:hover {
            background-color: var(--rc-surface-2, #f1f5f9);
            color: var(--rc-text, #0f172a);
            border-color: var(--rc-border-2, #cbd5e1);
            transform: translateY(-1px);
        }

        .rc-report-tab-btn.active {
            background: linear-gradient(135deg, var(--rc-emerald, #10b981), var(--rc-emerald-hover, #059669)) !important;
            border-color: rgba(255, 255, 255, 0.15) !important;
            color: #ffffff !important;
            box-shadow: 0 4px 14px -2px rgba(16, 185, 129, 0.45);
        }

        .rc-report-tab-btn.active svg {
            color: #ffffff !important;
        }

        /* 2. Filter & Action Bars */
        .rc-report-filter-bar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1rem 1.25rem;
            background-color: var(--rc-surface, #ffffff);
            border: 1px solid var(--rc-border, #e2e8f0);
            border-radius: 1rem;
            box-shadow: var(--rc-shadow-sm, 0 1px 3px rgba(15, 23, 42, 0.04));
            position: relative;
            z-index: 20;
        }

        .rc-report-filter-group {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 1.25rem;
        }

        .rc-report-filter-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            position: relative;
        }

        .rc-report-label {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--rc-text-subtle, #94a3b8);
            white-space: nowrap;
        }

        .rc-report-input {
            padding: 0.48rem 0.85rem;
            border-radius: 0.6rem;
            border: 1px solid var(--rc-border-2, #cbd5e1);
            background-color: var(--rc-surface-2, #f1f5f9);
            color: var(--rc-text, #0f172a);
            font-size: 0.8125rem;
            font-weight: 500;
            outline: none;
            transition: all 0.2s ease;
        }

        .rc-report-input:focus {
            border-color: var(--rc-emerald, #10b981);
            box-shadow: 0 0 0 3px var(--rc-emerald-dim, rgba(16, 185, 129, 0.12));
        }

        /* Luxury Custom Select Component (Alpine.js Popover) */
        .rc-custom-select {
            position: relative;
            display: inline-block;
        }

        .rc-select-btn {
            display: inline-flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            padding: 0.48rem 0.9rem;
            background-color: var(--rc-surface-2, #f1f5f9);
            border: 1px solid var(--rc-border-2, #cbd5e1);
            border-radius: 0.6rem;
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--rc-text, #0f172a);
            cursor: pointer;
            min-width: 145px;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: var(--rc-shadow-sm, 0 1px 2px rgba(0,0,0,0.03));
        }

        .rc-select-btn:hover {
            border-color: var(--rc-emerald, #10b981);
            background-color: var(--rc-surface, #ffffff);
            transform: translateY(-1px);
            box-shadow: 0 2px 8px -2px var(--rc-emerald-dim, rgba(16, 185, 129, 0.2));
        }

        .rc-select-chevron {
            width: 13px !important;
            height: 13px !important;
            stroke: var(--rc-emerald, #10b981);
            transition: transform 0.2s ease;
            flex-shrink: 0;
        }

        .rc-select-chevron.open {
            transform: rotate(180deg);
        }

        .rc-select-panel {
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            min-width: 180px;
            max-height: 280px;
            overflow-y: auto;
            background-color: var(--rc-surface, #ffffff);
            border: 1px solid var(--rc-border, #e2e8f0);
            border-radius: 0.85rem;
            box-shadow: var(--rc-shadow-pop, 0 16px 32px -8px rgba(15, 23, 42, 0.14));
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            padding: 0.35rem;
            z-index: 999;
            display: flex;
            flex-direction: column;
            gap: 2px;
            animation: rcFadeInDown 0.15s ease both;
        }

        @keyframes rcFadeInDown {
            from { opacity: 0; transform: translateY(-6px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .rc-select-option {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            border: none;
            background: transparent;
            color: var(--rc-text, #0f172a);
            font-size: 0.8125rem;
            font-weight: 500;
            cursor: pointer;
            text-align: left;
            white-space: nowrap;
            transition: all 0.15s ease;
        }

        .rc-select-option:hover {
            background-color: var(--rc-emerald-dim, rgba(16, 185, 129, 0.12));
            color: var(--rc-emerald, #10b981);
            transform: translateX(3px);
        }

        .rc-select-option.active {
            background-color: var(--rc-emerald-dim, rgba(16, 185, 129, 0.12));
            color: var(--rc-emerald, #10b981);
            font-weight: 700;
        }

        .rc-select-check {
            width: 14px !important;
            height: 14px !important;
            stroke: var(--rc-emerald, #10b981);
            stroke-width: 2.5;
            flex-shrink: 0;
        }

        /* Action Buttons (Excel & PDF) */
        .rc-report-actions {
            display: flex;
            align-items: center;
            gap: 0.625rem;
        }

        .rc-report-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.55rem 1.1rem;
            border-radius: 0.75rem;
            font-size: 0.78rem;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            border: none;
            box-shadow: 0 2px 6px -1px rgba(0,0,0,0.08);
            white-space: nowrap;
        }

        .rc-report-btn svg {
            width: 16px !important;
            height: 16px !important;
            color: #ffffff !important;
        }

        .rc-report-btn-emerald {
            background: linear-gradient(135deg, #059669, #047857);
            color: #ffffff !important;
        }

        .rc-report-btn-emerald:hover {
            filter: brightness(1.08);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px -2px rgba(5, 150, 105, 0.45);
        }

        .rc-report-btn-indigo {
            background: linear-gradient(135deg, #4f46e5, #4338ca);
            color: #ffffff !important;
        }

        .rc-report-btn-indigo:hover {
            filter: brightness(1.08);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px -2px rgba(79, 70, 229, 0.45);
        }

        .rc-report-btn:active {
            transform: translateY(0) scale(0.97);
        }

        /* 3. KPI Overview Cards Grid */
        .rc-report-kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
            gap: 1rem;
        }

        .rc-report-kpi-card {
            background-color: var(--rc-surface, #ffffff);
            border: 1px solid var(--rc-border, #e2e8f0);
            border-radius: 1rem;
            padding: 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: var(--rc-shadow-sm, 0 1px 3px rgba(15, 23, 42, 0.04));
            transition: transform 0.25s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow 0.25s ease, border-color 0.25s ease;
        }

        .rc-report-kpi-card:hover {
            transform: translateY(-3px);
            border-color: var(--rc-border-2, #cbd5e1);
            box-shadow: var(--rc-shadow-md, 0 8px 24px -4px rgba(15, 23, 42, 0.08));
        }

        .rc-report-kpi-icon {
            width: 48px;
            height: 48px;
            min-width: 48px;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .rc-report-kpi-icon svg {
            width: 24px !important;
            height: 24px !important;
        }

        .rc-report-kpi-title {
            font-size: 0.6875rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--rc-text-subtle, #94a3b8);
        }

        .rc-report-kpi-value {
            font-family: var(--font-heading, 'Plus Jakarta Sans', sans-serif);
            font-size: 1.2rem;
            font-weight: 800;
            letter-spacing: -0.025em;
            color: var(--rc-text, #0f172a);
            margin-top: 0.2rem;
            white-space: nowrap !important;
        }

        /* KPI Icon Themes */
        .icon-emerald { background-color: rgba(16, 185, 129, 0.14); color: #10b981; }
        .icon-indigo  { background-color: rgba(99, 102, 241, 0.14); color: #6366f1; }
        .icon-sky     { background-color: rgba(14, 165, 233, 0.14); color: #0ea5e9; }
        .icon-amber   { background-color: rgba(245, 158, 11, 0.14); color: #f59e0b; }
        .icon-rose    { background-color: rgba(244, 63, 94, 0.14);  color: #f43f5e; }

        /* 4. Table Preview Container */
        .rc-report-table-card {
            background-color: var(--rc-surface, #ffffff);
            border: 1px solid var(--rc-border, #e2e8f0);
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: var(--rc-shadow-sm, 0 1px 3px rgba(15, 23, 42, 0.04));
        }

        .rc-report-table-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--rc-border, #e2e8f0);
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--rc-text, #0f172a);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .rc-report-table-subtitle {
            font-size: 0.75rem;
            font-weight: 400;
            color: var(--rc-text-muted, #64748b);
            margin-left: 0.35rem;
        }

        .rc-report-table-scroll {
            overflow-x: auto;
            width: 100%;
        }

        .rc-report-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.8125rem;
        }

        .rc-report-table th {
            background-color: var(--rc-surface-2, #f1f5f9);
            color: var(--rc-text-subtle, #94a3b8);
            font-size: 0.6875rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--rc-border, #e2e8f0);
            text-align: left;
            white-space: nowrap;
        }

        .rc-report-table td {
            padding: 0.85rem 1rem;
            border-bottom: 1px solid var(--rc-border, #e2e8f0);
            color: var(--rc-text, #0f172a);
            vertical-align: middle;
            text-align: left;
        }

        .rc-report-table tbody tr:last-child td {
            border-bottom: none;
        }

        .rc-report-table tbody tr {
            transition: background-color 0.15s ease;
        }

        .rc-report-table tbody tr:hover {
            background-color: var(--rc-surface-2, #f8fafc);
        }

        html.dark .rc-report-table tbody tr:hover {
            background-color: #141e2e;
        }

        /* 5. Status Badges */
        .rc-report-badge {
            display: inline-block;
            padding: 0.25rem 0.65rem;
            border-radius: 9999px;
            font-size: 0.65rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            line-height: 1.2;
            text-align: center;
        }

        .badge-paid {
            background-color: rgba(16, 185, 129, 0.15);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.35);
        }

        .badge-pending {
            background-color: rgba(245, 158, 11, 0.15);
            color: #f59e0b;
            border: 1px solid rgba(245, 158, 11, 0.35);
        }

        .badge-info {
            background-color: rgba(59, 130, 246, 0.15);
            color: #3b82f6;
            border: 1px solid rgba(59, 130, 246, 0.35);
        }

        .badge-danger {
            background-color: rgba(244, 63, 94, 0.15);
            color: #f43f5e;
            border: 1px solid rgba(244, 63, 94, 0.35);
        }

        /* Rank badges */
        .rc-report-rank {
            width: 24px;
            height: 24px;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 900;
        }
        .rank-1 { background: #fbbf24; color: #1e1b4b; }
        .rank-2 { background: #cbd5e1; color: #0f172a; }
        .rank-3 { background: #d97706; color: #ffffff; }

        /* Invoice Link */
        .rc-report-link-invoice {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.75rem;
            font-weight: 600;
            color: #6366f1;
            text-decoration: none;
            transition: color 0.15s ease;
        }

        .rc-report-link-invoice svg {
            width: 14px !important;
            height: 14px !important;
        }

        .rc-report-link-invoice:hover {
            text-decoration: underline;
            color: #4f46e5;
        }
    </style>

    <div class="rc-report-wrapper">
        {{-- Navigation Category Tabs --}}
        <div class="rc-report-tabs">
            <button
                type="button"
                wire:click="setActiveTab('sales')"
                class="rc-report-tab-btn {{ $activeTab === 'sales' ? 'active' : '' }}"
            >
                <x-heroicon-m-banknotes />
                <span>Penjualan & Omset</span>
            </button>

            <button
                type="button"
                wire:click="setActiveTab('inventory')"
                class="rc-report-tab-btn {{ $activeTab === 'inventory' ? 'active' : '' }}"
            >
                <x-heroicon-m-cube />
                <span>Stok & Inventaris</span>
            </button>

            <button
                type="button"
                wire:click="setActiveTab('bestseller')"
                class="rc-report-tab-btn {{ $activeTab === 'bestseller' ? 'active' : '' }}"
            >
                <x-heroicon-m-fire />
                <span>Produk Terlaris</span>
            </button>

            <button
                type="button"
                wire:click="setActiveTab('customers')"
                class="rc-report-tab-btn {{ $activeTab === 'customers' ? 'active' : '' }}"
            >
                <x-heroicon-m-user-group />
                <span>Pelanggan Loyal</span>
            </button>
        </div>

        {{-- TAB 1: PENJUALAN & OMSET --}}
        @if($activeTab === 'sales')
            @php $salesData = $this->getSalesData(); @endphp

            {{-- Filter & Action Bar --}}
            <div class="rc-report-filter-bar">
                <div class="rc-report-filter-group">
                    <div class="rc-report-filter-item">
                        <label class="rc-report-label">Dari:</label>
                        <input
                            type="date"
                            wire:model.live="salesStartDate"
                            class="rc-report-input"
                        />
                    </div>
                    <div class="rc-report-filter-item">
                        <label class="rc-report-label">Sampai:</label>
                        <input
                            type="date"
                            wire:model.live="salesEndDate"
                            class="rc-report-input"
                        />
                    </div>

                    {{-- Custom Luxury Dropdown: Status --}}
                    @php
                        $salesStatusOptions = [
                            'all' => 'Semua Status',
                            'paid' => 'Lunas (Paid)',
                            'pending' => 'Menunggu Bayar',
                            'processing' => 'Diproses',
                            'shipped' => 'Dikirim',
                            'delivered' => 'Selesai (Delivered)',
                            'cancelled' => 'Dibatalkan',
                        ];
                    @endphp
                    <div class="rc-report-filter-item" x-data="{ open: false }" @click.outside="open = false">
                        <label class="rc-report-label">Status:</label>
                        <div class="rc-custom-select">
                            <button type="button" @click="open = !open" class="rc-select-btn">
                                <span>{{ $salesStatusOptions[$salesStatus] ?? 'Semua Status' }}</span>
                                <svg class="rc-select-chevron" :class="{ 'open': open }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                                </svg>
                            </button>
                            <div x-show="open" x-cloak class="rc-select-panel">
                                @foreach($salesStatusOptions as $val => $label)
                                    <button
                                        type="button"
                                        wire:click="$set('salesStatus', '{{ $val }}')"
                                        @click="open = false"
                                        class="rc-select-option {{ $salesStatus === $val ? 'active' : '' }}"
                                    >
                                        <span>{{ $label }}</span>
                                        @if($salesStatus === $val)
                                            <svg class="rc-select-check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                            </svg>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons: Excel (.xlsx) & PDF --}}
                <div class="rc-report-actions">
                    <button
                        type="button"
                        wire:click="exportSalesExcel"
                        wire:loading.attr="disabled"
                        class="rc-report-btn rc-report-btn-emerald"
                        title="Unduh file Excel (.xlsx) resmi"
                    >
                        <x-heroicon-m-arrow-down-tray />
                        <span>Ekspor Excel (.xlsx)</span>
                    </button>

                    <a
                        href="{{ route('admin.reports.print.sales', ['start_date' => $salesStartDate, 'end_date' => $salesEndDate, 'status' => $salesStatus]) }}"
                        target="_blank"
                        class="rc-report-btn rc-report-btn-indigo"
                    >
                        <x-heroicon-m-printer />
                        <span>Cetak / PDF</span>
                    </a>
                </div>
            </div>

            {{-- Stat Cards --}}
            <div class="rc-report-kpi-grid">
                <div class="rc-report-kpi-card">
                    <div class="rc-report-kpi-icon icon-emerald">
                        <x-heroicon-o-currency-dollar />
                    </div>
                    <div>
                        <div class="rc-report-kpi-title">Total Omset (Lunas)</div>
                        <div class="rc-report-kpi-value">
                            Rp&nbsp;{{ number_format($salesData['summary']['total_revenue'], 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <div class="rc-report-kpi-card">
                    <div class="rc-report-kpi-icon icon-indigo">
                        <x-heroicon-o-shopping-bag />
                    </div>
                    <div>
                        <div class="rc-report-kpi-title">Total Transaksi</div>
                        <div class="rc-report-kpi-value">
                            {{ $salesData['summary']['total_orders'] }} Pesanan
                        </div>
                    </div>
                </div>

                <div class="rc-report-kpi-card">
                    <div class="rc-report-kpi-icon icon-sky">
                        <x-heroicon-o-check-badge />
                    </div>
                    <div>
                        <div class="rc-report-kpi-title">Pesanan Lunas</div>
                        <div class="rc-report-kpi-value">
                            {{ $salesData['summary']['paid_orders_count'] }} Pesanan
                        </div>
                    </div>
                </div>

                <div class="rc-report-kpi-card">
                    <div class="rc-report-kpi-icon icon-amber">
                        <x-heroicon-o-archive-box />
                    </div>
                    <div>
                        <div class="rc-report-kpi-title">Produk Terjual</div>
                        <div class="rc-report-kpi-value">
                            {{ $salesData['summary']['total_items_sold'] }} Unit
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table Preview --}}
            <div class="rc-report-table-card">
                <div class="rc-report-table-header">
                    <div>
                        Pratinjau Data Pesanan <span class="rc-report-table-subtitle">(Menampilkan {{ count($salesData['orders']) }} dari {{ $salesData['total_count'] }} transaksi)</span>
                    </div>
                </div>

                <div class="rc-report-table-scroll">
                    <table class="rc-report-table">
                        <thead>
                            <tr>
                                <th style="width: 45px;">No</th>
                                <th>No Pesanan</th>
                                <th>Waktu</th>
                                <th>Pembeli</th>
                                <th>Metode Bayar</th>
                                <th>Status</th>
                                <th style="width: 140px; white-space: nowrap;">Total Tagihan</th>
                                <th style="width: 90px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($salesData['orders'] as $idx => $order)
                                <tr>
                                    <td style="color: var(--rc-text-subtle);">{{ $idx + 1 }}</td>
                                    <td style="font-family: monospace; font-weight: 700;">{{ $order->order_number }}</td>
                                    <td style="color: var(--rc-text-muted);">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <div style="font-weight: 600;">{{ $order->shipping_name }}</div>
                                        <div style="font-size: 0.72rem; color: var(--rc-text-subtle);">{{ $order->shipping_phone }}</div>
                                    </td>
                                    <td>{{ strtoupper($order->payment?->payment_type ?? 'Online') }}</td>
                                    <td>
                                        @php
                                            $badgeClass = match($order->status) {
                                                'paid', 'delivered' => 'badge-paid',
                                                'pending' => 'badge-pending',
                                                'cancelled' => 'badge-danger',
                                                default => 'badge-info'
                                            };
                                        @endphp
                                        <span class="rc-report-badge {{ $badgeClass }}">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td style="font-weight: 800; white-space: nowrap;">
                                        Rp&nbsp;{{ number_format($order->total, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <a
                                            href="{{ route('admin.orders.invoice', $order->id) }}"
                                            target="_blank"
                                            title="Cetak Invoice"
                                            class="rc-report-link-invoice"
                                        >
                                            <x-heroicon-m-document-text />
                                            <span>Invoice</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" style="text-align: center; padding: 2rem; color: var(--rc-text-subtle);">
                                        Tidak ada data pesanan pada periode atau filter yang dipilih.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- TAB 2: STOK & INVENTARIS --}}
        @if($activeTab === 'inventory')
            @php $invData = $this->getInventoryData(); @endphp

            {{-- Filter & Action Bar --}}
            <div class="rc-report-filter-bar">
                <div class="rc-report-filter-group">
                    {{-- Custom Luxury Dropdown: Kategori --}}
                    <div class="rc-report-filter-item" x-data="{ open: false }" @click.outside="open = false">
                        <label class="rc-report-label">Kategori:</label>
                        <div class="rc-custom-select">
                            <button type="button" @click="open = !open" class="rc-select-btn">
                                <span>
                                    @if($inventoryCategoryId === 'all')
                                        Semua Kategori
                                    @else
                                        {{ $invData['categories']->firstWhere('id', $inventoryCategoryId)?->name ?? 'Semua Kategori' }}
                                    @endif
                                </span>
                                <svg class="rc-select-chevron" :class="{ 'open': open }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                                </svg>
                            </button>
                            <div x-show="open" x-cloak class="rc-select-panel">
                                <button
                                    type="button"
                                    wire:click="$set('inventoryCategoryId', 'all')"
                                    @click="open = false"
                                    class="rc-select-option {{ $inventoryCategoryId === 'all' ? 'active' : '' }}"
                                >
                                    <span>Semua Kategori</span>
                                    @if($inventoryCategoryId === 'all')
                                        <svg class="rc-select-check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                        </svg>
                                    @endif
                                </button>
                                @foreach($invData['categories'] as $cat)
                                    <button
                                        type="button"
                                        wire:click="$set('inventoryCategoryId', '{{ $cat->id }}')"
                                        @click="open = false"
                                        class="rc-select-option {{ (string) $inventoryCategoryId === (string) $cat->id ? 'active' : '' }}"
                                    >
                                        <span>{{ $cat->name }}</span>
                                        @if((string) $inventoryCategoryId === (string) $cat->id)
                                            <svg class="rc-select-check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                            </svg>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Custom Luxury Dropdown: Kondisi Stok --}}
                    @php
                        $stockFilterOptions = [
                            'all' => 'Semua Produk',
                            'out_of_stock' => 'Stok Habis (0)',
                            'low_stock' => 'Stok Kritis (≤ 10)',
                            'in_stock' => 'Stok Aman (> 10)',
                        ];
                    @endphp
                    <div class="rc-report-filter-item" x-data="{ open: false }" @click.outside="open = false">
                        <label class="rc-report-label">Kondisi Stok:</label>
                        <div class="rc-custom-select">
                            <button type="button" @click="open = !open" class="rc-select-btn">
                                <span>{{ $stockFilterOptions[$inventoryStockFilter] ?? 'Semua Produk' }}</span>
                                <svg class="rc-select-chevron" :class="{ 'open': open }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                                </svg>
                            </button>
                            <div x-show="open" x-cloak class="rc-select-panel">
                                @foreach($stockFilterOptions as $val => $label)
                                    <button
                                        type="button"
                                        wire:click="$set('inventoryStockFilter', '{{ $val }}')"
                                        @click="open = false"
                                        class="rc-select-option {{ $inventoryStockFilter === $val ? 'active' : '' }}"
                                    >
                                        <span>{{ $label }}</span>
                                        @if($inventoryStockFilter === $val)
                                            <svg class="rc-select-check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                            </svg>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Export Actions: Excel (.xlsx) & PDF --}}
                <div class="rc-report-actions">
                    <button
                        type="button"
                        wire:click="exportInventoryExcel"
                        wire:loading.attr="disabled"
                        class="rc-report-btn rc-report-btn-emerald"
                        title="Unduh file Excel (.xlsx) resmi"
                    >
                        <x-heroicon-m-arrow-down-tray />
                        <span>Ekspor Excel (.xlsx)</span>
                    </button>

                    <a
                        href="{{ route('admin.reports.print.inventory', ['category_id' => $inventoryCategoryId, 'stock_filter' => $inventoryStockFilter]) }}"
                        target="_blank"
                        class="rc-report-btn rc-report-btn-indigo"
                    >
                        <x-heroicon-m-printer />
                        <span>Cetak / PDF</span>
                    </a>
                </div>
            </div>

            {{-- Stat Cards --}}
            <div class="rc-report-kpi-grid">
                <div class="rc-report-kpi-card">
                    <div class="rc-report-kpi-icon icon-indigo">
                        <x-heroicon-o-cube />
                    </div>
                    <div>
                        <div class="rc-report-kpi-title">Total Unit Fisik</div>
                        <div class="rc-report-kpi-value">
                            {{ number_format($invData['summary']['total_units'], 0, ',', '.') }} Unit
                        </div>
                    </div>
                </div>

                <div class="rc-report-kpi-card">
                    <div class="rc-report-kpi-icon icon-emerald">
                        <x-heroicon-o-calculator />
                    </div>
                    <div>
                        <div class="rc-report-kpi-title">Valuasi Nilai Aset</div>
                        <div class="rc-report-kpi-value">
                            Rp&nbsp;{{ number_format($invData['summary']['total_valuation'], 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <div class="rc-report-kpi-card">
                    <div class="rc-report-kpi-icon icon-rose">
                        <x-heroicon-o-exclamation-triangle />
                    </div>
                    <div>
                        <div class="rc-report-kpi-title">Stok Habis (Kosong)</div>
                        <div class="rc-report-kpi-value" style="color: #f43f5e;">
                            {{ $invData['summary']['out_of_stock'] }} Produk
                        </div>
                    </div>
                </div>

                <div class="rc-report-kpi-card">
                    <div class="rc-report-kpi-icon icon-amber">
                        <x-heroicon-o-clock />
                    </div>
                    <div>
                        <div class="rc-report-kpi-title">Stok Kritis (≤ 10)</div>
                        <div class="rc-report-kpi-value" style="color: #f59e0b;">
                            {{ $invData['summary']['low_stock'] }} Produk
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table Preview --}}
            <div class="rc-report-table-card">
                <div class="rc-report-table-header">
                    <div>
                        Pratinjau Inventaris Produk <span class="rc-report-table-subtitle">(Menampilkan {{ count($invData['products']) }} dari {{ $invData['total_count'] }} produk)</span>
                    </div>
                </div>

                <div class="rc-report-table-scroll">
                    <table class="rc-report-table">
                        <thead>
                            <tr>
                                <th style="width: 45px;">No</th>
                                <th style="width: 110px;">SKU</th>
                                <th>Nama Produk</th>
                                <th style="width: 160px;">Kategori</th>
                                <th style="width: 140px; white-space: nowrap;">Harga Satuan</th>
                                <th style="width: 90px;">Sisa Stok</th>
                                <th style="width: 150px; white-space: nowrap;">Total Valuasi</th>
                                <th style="width: 100px;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invData['products'] as $idx => $prod)
                                <tr>
                                    <td style="color: var(--rc-text-subtle);">{{ $idx + 1 }}</td>
                                    <td style="font-family: monospace; font-weight: 700;">{{ $prod->sku ?: '-' }}</td>
                                    <td style="font-weight: 600;">{{ $prod->name }}</td>
                                    <td style="color: var(--rc-text-muted);">{{ $prod->category?->name ?? 'Tanpa Kategori' }}</td>
                                    <td style="white-space: nowrap;">Rp&nbsp;{{ number_format($prod->price, 0, ',', '.') }}</td>
                                    <td style="font-weight: 800;">{{ $prod->stock }} Unit</td>
                                    <td style="font-weight: 800; white-space: nowrap;">
                                        Rp&nbsp;{{ number_format($prod->stock * ($prod->price ?? 0), 0, ',', '.') }}
                                    </td>
                                    <td>
                                        @if($prod->stock <= 0)
                                            <span class="rc-report-badge badge-danger">HABIS</span>
                                        @elseif($prod->stock <= 10)
                                            <span class="rc-report-badge badge-pending">MENIPIS</span>
                                        @else
                                            <span class="rc-report-badge badge-paid">TERSEDIA</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" style="text-align: center; padding: 2rem; color: var(--rc-text-subtle);">
                                        Tidak ada produk yang cocok dengan kriteria filter.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- TAB 3: PRODUK TERLARIS --}}
        @if($activeTab === 'bestseller')
            @php $bsData = $this->getBestSellerData(); @endphp

            {{-- Filter & Action Bar --}}
            <div class="rc-report-filter-bar">
                <div class="rc-report-filter-group">
                    {{-- Custom Luxury Dropdown: Rentang Waktu --}}
                    @php
                        $daysOptions = [
                            7 => '7 Hari Terakhir',
                            30 => '30 Hari Terakhir',
                            90 => '3 Bulan Terakhir',
                            365 => '1 Tahun Terakhir',
                        ];
                    @endphp
                    <div class="rc-report-filter-item" x-data="{ open: false }" @click.outside="open = false">
                        <label class="rc-report-label">Rentang Waktu:</label>
                        <div class="rc-custom-select">
                            <button type="button" @click="open = !open" class="rc-select-btn">
                                <span>{{ $daysOptions[$bestsellerDays] ?? '30 Hari Terakhir' }}</span>
                                <svg class="rc-select-chevron" :class="{ 'open': open }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                                </svg>
                            </button>
                            <div x-show="open" x-cloak class="rc-select-panel">
                                @foreach($daysOptions as $dVal => $dLabel)
                                    <button
                                        type="button"
                                        wire:click="$set('bestsellerDays', {{ $dVal }})"
                                        @click="open = false"
                                        class="rc-select-option {{ $bestsellerDays == $dVal ? 'active' : '' }}"
                                    >
                                        <span>{{ $dLabel }}</span>
                                        @if($bestsellerDays == $dVal)
                                            <svg class="rc-select-check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                            </svg>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Custom Luxury Dropdown: Tampilkan Limit --}}
                    @php
                        $limitOptions = [
                            5 => 'Top 5',
                            10 => 'Top 10',
                            25 => 'Top 25',
                            50 => 'Top 50',
                        ];
                    @endphp
                    <div class="rc-report-filter-item" x-data="{ open: false }" @click.outside="open = false">
                        <label class="rc-report-label">Tampilkan:</label>
                        <div class="rc-custom-select">
                            <button type="button" @click="open = !open" class="rc-select-btn" style="min-width: 110px;">
                                <span>{{ $limitOptions[$bestsellerLimit] ?? 'Top 10' }}</span>
                                <svg class="rc-select-chevron" :class="{ 'open': open }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                                </svg>
                            </button>
                            <div x-show="open" x-cloak class="rc-select-panel" style="min-width: 120px;">
                                @foreach($limitOptions as $lVal => $lLabel)
                                    <button
                                        type="button"
                                        wire:click="$set('bestsellerLimit', {{ $lVal }})"
                                        @click="open = false"
                                        class="rc-select-option {{ $bestsellerLimit == $lVal ? 'active' : '' }}"
                                    >
                                        <span>{{ $lLabel }}</span>
                                        @if($bestsellerLimit == $lVal)
                                            <svg class="rc-select-check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                            </svg>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rc-report-actions">
                    <button
                        type="button"
                        wire:click="exportBestSellerExcel"
                        wire:loading.attr="disabled"
                        class="rc-report-btn rc-report-btn-emerald"
                        title="Unduh file Excel (.xlsx) resmi"
                    >
                        <x-heroicon-m-arrow-down-tray />
                        <span>Ekspor Excel (.xlsx)</span>
                    </button>
                </div>
            </div>

            {{-- Table --}}
            <div class="rc-report-table-card">
                <div class="rc-report-table-header">
                    <div>
                        Daftar Produk Terlaris <span class="rc-report-table-subtitle">(Periode {{ $bestsellerDays }} Hari Terakhir)</span>
                    </div>
                </div>

                <div class="rc-report-table-scroll">
                    <table class="rc-report-table">
                        <thead>
                            <tr>
                                <th style="width: 60px;">Rank</th>
                                <th>Nama Produk</th>
                                <th style="width: 140px;">Total Terjual</th>
                                <th style="width: 180px; white-space: nowrap;">Total Nilai Penjualan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bsData['items'] as $idx => $item)
                                <tr>
                                    <td>
                                        @if($idx === 0)
                                            <span class="rc-report-rank rank-1">1</span>
                                        @elseif($idx === 1)
                                            <span class="rc-report-rank rank-2">2</span>
                                        @elseif($idx === 2)
                                            <span class="rc-report-rank rank-3">3</span>
                                        @else
                                            <span style="font-weight: 700; color: var(--rc-text-subtle);">{{ $idx + 1 }}</span>
                                        @endif
                                    </td>
                                    <td style="font-weight: 600;">{{ $item->product_name }}</td>
                                    <td style="font-weight: 800; color: #4f46e5;">{{ $item->total_sold }} Unit</td>
                                    <td style="font-weight: 800; white-space: nowrap;">Rp&nbsp;{{ number_format($item->total_revenue, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" style="text-align: center; padding: 2rem; color: var(--rc-text-subtle);">
                                        Belum ada data produk terjual pada periode ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- TAB 4: PELANGGAN LOYAL --}}
        @if($activeTab === 'customers')
            @php $custData = $this->getCustomerData(); @endphp

            {{-- Filter & Action Bar --}}
            <div class="rc-report-filter-bar">
                <div class="rc-report-filter-group">
                    <div class="rc-report-filter-item">
                        <label class="rc-report-label">Minimal Pesanan:</label>
                        <input
                            type="number"
                            min="1"
                            wire:model.live="customerMinOrders"
                            class="rc-report-input"
                            style="width: 80px;"
                        />
                    </div>
                </div>

                <div class="rc-report-actions">
                    <button
                        type="button"
                        wire:click="exportCustomerExcel"
                        wire:loading.attr="disabled"
                        class="rc-report-btn rc-report-btn-emerald"
                        title="Unduh file Excel (.xlsx) resmi"
                    >
                        <x-heroicon-m-arrow-down-tray />
                        <span>Ekspor Excel (.xlsx)</span>
                    </button>
                </div>
            </div>

            {{-- Table --}}
            <div class="rc-report-table-card">
                <div class="rc-report-table-header">
                    <div>
                        Peringkat Pelanggan Berdasarkan Total Akumulasi Belanja
                    </div>
                </div>

                <div class="rc-report-table-scroll">
                    <table class="rc-report-table">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Nama Pelanggan</th>
                                <th>Kontak & Email</th>
                                <th style="width: 170px;">Jumlah Transaksi Lunas</th>
                                <th style="width: 180px; white-space: nowrap;">Total Akumulasi Belanja</th>
                                <th style="width: 150px;">Transaksi Terakhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($custData['customers'] as $idx => $cust)
                                <tr>
                                    <td style="color: var(--rc-text-subtle);">{{ $idx + 1 }}</td>
                                    <td style="font-weight: 600;">{{ $cust->shipping_name }}</td>
                                    <td>
                                        <div style="font-weight: 500;">{{ $cust->shipping_phone }}</div>
                                        <div style="font-size: 0.72rem; color: var(--rc-text-subtle);">{{ $cust->customer_email ?: '-' }}</div>
                                    </td>
                                    <td style="font-weight: 800; color: #4f46e5;">{{ $cust->order_count }} Pesanan</td>
                                    <td style="font-weight: 900; white-space: nowrap;">Rp&nbsp;{{ number_format($cust->total_spent, 0, ',', '.') }}</td>
                                    <td style="color: var(--rc-text-muted);">
                                        {{ $cust->last_order_at ? \Illuminate\Support\Carbon::parse($cust->last_order_at)->format('d/m/Y H:i') : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 2.5rem 1rem; color: var(--rc-text-subtle);">
                                        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.5rem;">
                                            <x-heroicon-o-user-group style="width: 36px; height: 36px; opacity: 0.4;" />
                                            <span style="font-weight: 600; font-size: 0.875rem;">Belum Ada Data Pelanggan</span>
                                            <span style="font-size: 0.75rem; color: var(--rc-text-muted);">Belum ada pelanggan yang memenuhi kriteria minimal {{ $customerMinOrders }} pesanan lunas.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>
