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
        }

        .rc-report-filter-group {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 1rem;
        }

        .rc-report-filter-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .rc-report-label {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--rc-text-subtle, #94a3b8);
            white-space: nowrap;
        }

        .rc-report-input,
        .rc-report-select {
            padding: 0.45rem 0.85rem;
            border-radius: 0.5rem;
            border: 1px solid var(--rc-border-2, #cbd5e1);
            background-color: var(--rc-surface-2, #f1f5f9);
            color: var(--rc-text, #0f172a);
            font-size: 0.8125rem;
            font-weight: 500;
            outline: none;
            transition: all 0.2s ease;
        }

        .rc-report-input:focus,
        .rc-report-select:focus {
            border-color: var(--rc-emerald, #10b981);
            box-shadow: 0 0 0 3px var(--rc-emerald-dim, rgba(16, 185, 129, 0.12));
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
            padding: 0.55rem 1rem;
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
            background-color: #059669;
            color: #ffffff !important;
        }

        .rc-report-btn-emerald:hover {
            background-color: #047857;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px -2px rgba(5, 150, 105, 0.45);
        }

        .rc-report-btn-indigo {
            background-color: #4f46e5;
            color: #ffffff !important;
        }

        .rc-report-btn-indigo:hover {
            background-color: #4338ca;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px -2px rgba(79, 70, 229, 0.45);
        }

        .rc-report-btn:active {
            transform: translateY(0) scale(0.97);
        }

        /* 3. KPI Overview Cards Grid */
        .rc-report-kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
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
            font-size: 1.25rem;
            font-weight: 800;
            letter-spacing: -0.025em;
            color: var(--rc-text, #0f172a);
            margin-top: 0.2rem;
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
            white-space: nowrap;
        }

        .rc-report-table td {
            padding: 0.85rem 1rem;
            border-bottom: 1px solid var(--rc-border, #e2e8f0);
            color: var(--rc-text, #0f172a);
            vertical-align: middle;
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
                    <div class="rc-report-filter-item">
                        <label class="rc-report-label">Status:</label>
                        <select
                            wire:model.live="salesStatus"
                            class="rc-report-select"
                        >
                            <option value="all">Semua Status</option>
                            <option value="paid">Lunas (Paid)</option>
                            <option value="pending">Menunggu Bayar</option>
                            <option value="processing">Diproses</option>
                            <option value="shipped">Dikirim</option>
                            <option value="delivered">Selesai (Delivered)</option>
                            <option value="cancelled">Dibatalkan</option>
                        </select>
                    </div>
                </div>

                {{-- Action Buttons: Excel & PDF --}}
                <div class="rc-report-actions">
                    <button
                        type="button"
                        wire:click="exportSalesCsv"
                        wire:loading.attr="disabled"
                        class="rc-report-btn rc-report-btn-emerald"
                    >
                        <x-heroicon-m-arrow-down-tray />
                        <span>Ekspor Excel (.csv)</span>
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
                            Rp {{ number_format($salesData['summary']['total_revenue'], 0, ',', '.') }}
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
                                <th style="text-align: center; width: 45px;">No</th>
                                <th>No Pesanan</th>
                                <th>Waktu</th>
                                <th>Pembeli</th>
                                <th>Metode Bayar</th>
                                <th style="text-align: center;">Status</th>
                                <th style="text-align: right;">Total Tagihan</th>
                                <th style="text-align: center; width: 90px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($salesData['orders'] as $idx => $order)
                                <tr>
                                    <td style="text-align: center; color: var(--rc-text-subtle);">{{ $idx + 1 }}</td>
                                    <td style="font-family: monospace; font-weight: 700;">{{ $order->order_number }}</td>
                                    <td style="color: var(--rc-text-muted);">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <div style="font-weight: 600;">{{ $order->shipping_name }}</div>
                                        <div style="font-size: 0.72rem; color: var(--rc-text-subtle);">{{ $order->shipping_phone }}</div>
                                    </td>
                                    <td>{{ strtoupper($order->payment?->payment_type ?? 'Online') }}</td>
                                    <td style="text-align: center;">
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
                                    <td style="text-align: right; font-weight: 800;">
                                        Rp {{ number_format($order->total, 0, ',', '.') }}
                                    </td>
                                    <td style="text-align: center;">
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
                    <div class="rc-report-filter-item">
                        <label class="rc-report-label">Kategori:</label>
                        <select
                            wire:model.live="inventoryCategoryId"
                            class="rc-report-select"
                        >
                            <option value="all">Semua Kategori</option>
                            @foreach($invData['categories'] as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="rc-report-filter-item">
                        <label class="rc-report-label">Kondisi Stok:</label>
                        <select
                            wire:model.live="inventoryStockFilter"
                            class="rc-report-select"
                        >
                            <option value="all">Semua Produk</option>
                            <option value="out_of_stock">Stok Habis (0)</option>
                            <option value="low_stock">Stok Kritis (≤ 10)</option>
                            <option value="in_stock">Stok Aman (> 10)</option>
                        </select>
                    </div>
                </div>

                {{-- Export Actions --}}
                <div class="rc-report-actions">
                    <button
                        type="button"
                        wire:click="exportInventoryCsv"
                        wire:loading.attr="disabled"
                        class="rc-report-btn rc-report-btn-emerald"
                    >
                        <x-heroicon-m-arrow-down-tray />
                        <span>Ekspor Excel (.csv)</span>
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
                            Rp {{ number_format($invData['summary']['total_valuation'], 0, ',', '.') }}
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
                                <th style="text-align: center; width: 45px;">No</th>
                                <th>SKU</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th style="text-align: right;">Harga Satuan</th>
                                <th style="text-align: center;">Sisa Stok</th>
                                <th style="text-align: right;">Valuasi Stok</th>
                                <th style="text-align: center; width: 90px;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invData['products'] as $idx => $prod)
                                <tr>
                                    <td style="text-align: center; color: var(--rc-text-subtle);">{{ $idx + 1 }}</td>
                                    <td style="font-family: monospace; font-weight: 700;">{{ $prod->sku ?: '-' }}</td>
                                    <td style="font-weight: 600;">{{ $prod->name }}</td>
                                    <td style="color: var(--rc-text-muted);">{{ $prod->category?->name ?? 'Tanpa Kategori' }}</td>
                                    <td style="text-align: right;">Rp {{ number_format($prod->price, 0, ',', '.') }}</td>
                                    <td style="text-align: center; font-weight: 800;">{{ $prod->stock }}</td>
                                    <td style="text-align: right; font-weight: 800;">
                                        Rp {{ number_format($prod->stock * ($prod->price ?? 0), 0, ',', '.') }}
                                    </td>
                                    <td style="text-align: center;">
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
                    <div class="rc-report-filter-item">
                        <label class="rc-report-label">Rentang Waktu:</label>
                        <select
                            wire:model.live="bestsellerDays"
                            class="rc-report-select"
                        >
                            <option value="7">7 Hari Terakhir</option>
                            <option value="30">30 Hari Terakhir</option>
                            <option value="90">3 Bulan Terakhir</option>
                            <option value="365">1 Tahun Terakhir</option>
                        </select>
                    </div>

                    <div class="rc-report-filter-item">
                        <label class="rc-report-label">Tampilkan:</label>
                        <select
                            wire:model.live="bestsellerLimit"
                            class="rc-report-select"
                        >
                            <option value="5">Top 5</option>
                            <option value="10">Top 10</option>
                            <option value="25">Top 25</option>
                            <option value="50">Top 50</option>
                        </select>
                    </div>
                </div>

                <div class="rc-report-actions">
                    <button
                        type="button"
                        wire:click="exportBestSellerCsv"
                        wire:loading.attr="disabled"
                        class="rc-report-btn rc-report-btn-emerald"
                    >
                        <x-heroicon-m-arrow-down-tray />
                        <span>Ekspor Excel (.csv)</span>
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
                                <th style="text-align: center; width: 60px;">Rank</th>
                                <th>Nama Produk</th>
                                <th style="text-align: center;">Total Terjual</th>
                                <th style="text-align: right;">Total Nilai Penjualan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bsData['items'] as $idx => $item)
                                <tr>
                                    <td style="text-align: center;">
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
                                    <td style="text-align: center; font-weight: 800; color: #4f46e5;">{{ $item->total_sold }} Unit</td>
                                    <td style="text-align: right; font-weight: 800;">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</td>
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
                        wire:click="exportCustomerCsv"
                        wire:loading.attr="disabled"
                        class="rc-report-btn rc-report-btn-emerald"
                    >
                        <x-heroicon-m-arrow-down-tray />
                        <span>Ekspor Excel (.csv)</span>
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
                                <th style="text-align: center; width: 50px;">No</th>
                                <th>Nama Pelanggan</th>
                                <th>Kontak & Email</th>
                                <th style="text-align: center;">Jumlah Transaksi Lunas</th>
                                <th style="text-align: right;">Total Akumulasi Belanja</th>
                                <th style="text-align: center;">Transaksi Terakhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($custData['customers'] as $idx => $cust)
                                <tr>
                                    <td style="text-align: center; color: var(--rc-text-subtle);">{{ $idx + 1 }}</td>
                                    <td style="font-weight: 600;">{{ $cust->shipping_name }}</td>
                                    <td>
                                        <div style="font-weight: 500;">{{ $cust->shipping_phone }}</div>
                                        <div style="font-size: 0.72rem; color: var(--rc-text-subtle);">{{ $cust->shipping_email ?: '-' }}</div>
                                    </td>
                                    <td style="text-align: center; font-weight: 800; color: #4f46e5;">{{ $cust->order_count }} Pesanan</td>
                                    <td style="text-align: right; font-weight: 900;">Rp {{ number_format($cust->total_spent, 0, ',', '.') }}</td>
                                    <td style="text-align: center; color: var(--rc-text-muted);">{{ \Illuminate\Support\Carbon::parse($cust->last_order_at)->format('d/m/Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 2rem; color: var(--rc-text-subtle);">
                                        Belum ada data pelanggan yang memenuhi kriteria.
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
