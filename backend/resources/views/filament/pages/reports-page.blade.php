<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Navigation Category Tabs --}}
        <div class="flex flex-wrap items-center gap-2 border-b border-gray-200 dark:border-gray-800 pb-3">
            <button
                type="button"
                wire:click="setActiveTab('sales')"
                class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-sm {{ $activeTab === 'sales' ? 'bg-primary-600 text-white shadow-primary-500/20' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 border border-gray-200 dark:border-gray-700' }}"
            >
                <x-heroicon-m-banknotes class="w-4 h-4" />
                <span>Penjualan & Omset</span>
            </button>

            <button
                type="button"
                wire:click="setActiveTab('inventory')"
                class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-sm {{ $activeTab === 'inventory' ? 'bg-primary-600 text-white shadow-primary-500/20' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 border border-gray-200 dark:border-gray-700' }}"
            >
                <x-heroicon-m-cube class="w-4 h-4" />
                <span>Stok & Inventaris</span>
            </button>

            <button
                type="button"
                wire:click="setActiveTab('bestseller')"
                class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-sm {{ $activeTab === 'bestseller' ? 'bg-primary-600 text-white shadow-primary-500/20' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 border border-gray-200 dark:border-gray-700' }}"
            >
                <x-heroicon-m-fire class="w-4 h-4" />
                <span>Produk Terlaris</span>
            </button>

            <button
                type="button"
                wire:click="setActiveTab('customers')"
                class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-sm {{ $activeTab === 'customers' ? 'bg-primary-600 text-white shadow-primary-500/20' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 border border-gray-200 dark:border-gray-700' }}"
            >
                <x-heroicon-m-user-group class="w-4 h-4" />
                <span>Pelanggan Loyal</span>
            </button>
        </div>

        {{-- TAB 1: PENJUALAN & OMSET --}}
        @if($activeTab === 'sales')
            @php $salesData = $this->getSalesData(); @endphp

            {{-- Filter & Action Bar --}}
            <div class="p-4 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4">
                <div class="flex flex-wrap items-center gap-3">
                    <div class="flex items-center gap-2">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Dari:</label>
                        <input
                            type="date"
                            wire:model.live="salesStartDate"
                            class="px-3 py-1.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs font-medium focus:ring-2 focus:ring-primary-500"
                        />
                    </div>
                    <div class="flex items-center gap-2">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Sampai:</label>
                        <input
                            type="date"
                            wire:model.live="salesEndDate"
                            class="px-3 py-1.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs font-medium focus:ring-2 focus:ring-primary-500"
                        />
                    </div>
                    <div class="flex items-center gap-2">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Status:</label>
                        <select
                            wire:model.live="salesStatus"
                            class="px-3 py-1.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs font-medium focus:ring-2 focus:ring-primary-500"
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
                <div class="flex items-center gap-2">
                    <button
                        type="button"
                        wire:click="exportSalesCsv"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 active:scale-95 text-white font-semibold text-xs transition shadow-sm"
                    >
                        <x-heroicon-m-arrow-down-tray class="w-4 h-4" />
                        <span>Ekspor Excel (.csv)</span>
                    </button>

                    <a
                        href="{{ route('admin.reports.print.sales', ['start_date' => $salesStartDate, 'end_date' => $salesEndDate, 'status' => $salesStatus]) }}"
                        target="_blank"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 active:scale-95 text-white font-semibold text-xs transition shadow-sm"
                    >
                        <x-heroicon-m-printer class="w-4 h-4" />
                        <span>Cetak / PDF</span>
                    </a>
                </div>
            </div>

            {{-- Stat Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="p-5 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-950/50 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                        <x-heroicon-o-currency-dollar class="w-6 h-6" />
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Omset (Lunas)</div>
                        <div class="text-xl font-black text-gray-900 dark:text-white mt-0.5">
                            Rp {{ number_format($salesData['summary']['total_revenue'], 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <div class="p-5 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-indigo-100 dark:bg-indigo-950/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                        <x-heroicon-o-shopping-bag class="w-6 h-6" />
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Transaksi</div>
                        <div class="text-xl font-black text-gray-900 dark:text-white mt-0.5">
                            {{ $salesData['summary']['total_orders'] }} Pesanan
                        </div>
                    </div>
                </div>

                <div class="p-5 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-sky-100 dark:bg-sky-950/50 flex items-center justify-center text-sky-600 dark:text-sky-400">
                        <x-heroicon-o-check-badge class="w-6 h-6" />
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pesanan Lunas</div>
                        <div class="text-xl font-black text-gray-900 dark:text-white mt-0.5">
                            {{ $salesData['summary']['paid_orders_count'] }} Pesanan
                        </div>
                    </div>
                </div>

                <div class="p-5 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-950/50 flex items-center justify-center text-amber-600 dark:text-amber-400">
                        <x-heroicon-o-archive-box class="w-6 h-6" />
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Produk Terjual</div>
                        <div class="text-xl font-black text-gray-900 dark:text-white mt-0.5">
                            {{ $salesData['summary']['total_items_sold'] }} Unit
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table Preview --}}
            <div class="rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                    <div class="font-bold text-sm text-gray-800 dark:text-gray-200">
                        Pratinjau Data Pesanan <span class="text-xs text-gray-500 font-normal">(Menampilkan {{ count($salesData['orders']) }} dari {{ $salesData['total_count'] }} transaksi)</span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-gray-700 dark:text-gray-300">
                        <thead class="bg-gray-50 dark:bg-gray-800/60 uppercase tracking-wider font-semibold text-gray-500 border-b border-gray-200 dark:border-gray-800">
                            <tr>
                                <th class="px-4 py-3 text-center">No</th>
                                <th class="px-4 py-3">No Pesanan</th>
                                <th class="px-4 py-3">Waktu</th>
                                <th class="px-4 py-3">Pembeli</th>
                                <th class="px-4 py-3">Metode Bayar</th>
                                <th class="px-4 py-3 text-center">Status</th>
                                <th class="px-4 py-3 text-right">Total Tagihan</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @forelse($salesData['orders'] as $idx => $order)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition">
                                    <td class="px-4 py-3 text-center text-gray-400">{{ $idx + 1 }}</td>
                                    <td class="px-4 py-3 font-mono font-semibold text-gray-900 dark:text-white">{{ $order->order_number }}</td>
                                    <td class="px-4 py-3 text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-4 py-3">
                                        <div class="font-semibold text-gray-900 dark:text-white">{{ $order->shipping_name }}</div>
                                        <div class="text-[11px] text-gray-500">{{ $order->shipping_phone }}</div>
                                    </td>
                                    <td class="px-4 py-3">{{ strtoupper($order->payment?->payment_type ?? 'Online') }}</td>
                                    <td class="px-4 py-3 text-center">
                                        @php
                                            $badgeClass = match($order->status) {
                                                'paid', 'delivered' => 'bg-emerald-100 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-400 border border-emerald-300 dark:border-emerald-800',
                                                'pending' => 'bg-amber-100 dark:bg-amber-950/50 text-amber-700 dark:text-amber-400 border border-amber-300 dark:border-amber-800',
                                                default => 'bg-blue-100 dark:bg-blue-950/50 text-blue-700 dark:text-blue-400 border border-blue-300 dark:border-blue-800'
                                            };
                                        @endphp
                                        <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $badgeClass }}">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right font-bold text-gray-900 dark:text-white">
                                        Rp {{ number_format($order->total, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <a
                                            href="{{ route('admin.orders.invoice', $order->id) }}"
                                            target="_blank"
                                            title="Cetak Invoice"
                                            class="inline-flex items-center gap-1 text-[11px] font-semibold text-indigo-600 dark:text-indigo-400 hover:underline"
                                        >
                                            <x-heroicon-m-document-text class="w-3.5 h-3.5" />
                                            <span>Invoice</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-8 text-center text-gray-400">
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
            <div class="p-4 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4">
                <div class="flex flex-wrap items-center gap-3">
                    <div class="flex items-center gap-2">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori:</label>
                        <select
                            wire:model.live="inventoryCategoryId"
                            class="px-3 py-1.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs font-medium focus:ring-2 focus:ring-primary-500"
                        >
                            <option value="all">Semua Kategori</option>
                            @foreach($invData['categories'] as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Kondisi Stok:</label>
                        <select
                            wire:model.live="inventoryStockFilter"
                            class="px-3 py-1.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs font-medium focus:ring-2 focus:ring-primary-500"
                        >
                            <option value="all">Semua Produk</option>
                            <option value="out_of_stock">Stok Habis (0)</option>
                            <option value="low_stock">Stok Kritis (≤ 10)</option>
                            <option value="in_stock">Stok Aman (> 10)</option>
                        </select>
                    </div>
                </div>

                {{-- Export Actions --}}
                <div class="flex items-center gap-2">
                    <button
                        type="button"
                        wire:click="exportInventoryCsv"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 active:scale-95 text-white font-semibold text-xs transition shadow-sm"
                    >
                        <x-heroicon-m-arrow-down-tray class="w-4 h-4" />
                        <span>Ekspor Excel (.csv)</span>
                    </button>

                    <a
                        href="{{ route('admin.reports.print.inventory', ['category_id' => $inventoryCategoryId, 'stock_filter' => $inventoryStockFilter]) }}"
                        target="_blank"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 active:scale-95 text-white font-semibold text-xs transition shadow-sm"
                    >
                        <x-heroicon-m-printer class="w-4 h-4" />
                        <span>Cetak / PDF</span>
                    </a>
                </div>
            </div>

            {{-- Stat Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="p-5 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-primary-100 dark:bg-primary-950/50 flex items-center justify-center text-primary-600 dark:text-primary-400">
                        <x-heroicon-o-cube class="w-6 h-6" />
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Unit Fisik</div>
                        <div class="text-xl font-black text-gray-900 dark:text-white mt-0.5">
                            {{ number_format($invData['summary']['total_units'], 0, ',', '.') }} Unit
                        </div>
                    </div>
                </div>

                <div class="p-5 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-950/50 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                        <x-heroicon-o-calculator class="w-6 h-6" />
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Valuasi Nilai Aset</div>
                        <div class="text-xl font-black text-gray-900 dark:text-white mt-0.5">
                            Rp {{ number_format($invData['summary']['total_valuation'], 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <div class="p-5 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-rose-100 dark:bg-rose-950/50 flex items-center justify-center text-rose-600 dark:text-rose-400">
                        <x-heroicon-o-exclamation-triangle class="w-6 h-6" />
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Stok Habis (Kosong)</div>
                        <div class="text-xl font-black text-rose-600 dark:text-rose-400 mt-0.5">
                            {{ $invData['summary']['out_of_stock'] }} Produk
                        </div>
                    </div>
                </div>

                <div class="p-5 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-950/50 flex items-center justify-center text-amber-600 dark:text-amber-400">
                        <x-heroicon-o-clock class="w-6 h-6" />
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Stok Kritis (≤ 10)</div>
                        <div class="text-xl font-black text-amber-600 dark:text-amber-400 mt-0.5">
                            {{ $invData['summary']['low_stock'] }} Produk
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table Preview --}}
            <div class="rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                    <div class="font-bold text-sm text-gray-800 dark:text-gray-200">
                        Pratinjau Inventaris Produk <span class="text-xs text-gray-500 font-normal">(Menampilkan {{ count($invData['products']) }} dari {{ $invData['total_count'] }} produk)</span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-gray-700 dark:text-gray-300">
                        <thead class="bg-gray-50 dark:bg-gray-800/60 uppercase tracking-wider font-semibold text-gray-500 border-b border-gray-200 dark:border-gray-800">
                            <tr>
                                <th class="px-4 py-3 text-center">No</th>
                                <th class="px-4 py-3">SKU</th>
                                <th class="px-4 py-3">Nama Produk</th>
                                <th class="px-4 py-3">Kategori</th>
                                <th class="px-4 py-3 text-right">Harga Satuan</th>
                                <th class="px-4 py-3 text-center">Sisa Stok</th>
                                <th class="px-4 py-3 text-right">Valuasi Stok</th>
                                <th class="px-4 py-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @forelse($invData['products'] as $idx => $prod)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition">
                                    <td class="px-4 py-3 text-center text-gray-400">{{ $idx + 1 }}</td>
                                    <td class="px-4 py-3 font-mono font-semibold text-gray-900 dark:text-white">{{ $prod->sku ?: '-' }}</td>
                                    <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">{{ $prod->name }}</td>
                                    <td class="px-4 py-3">{{ $prod->category?->name ?? 'Tanpa Kategori' }}</td>
                                    <td class="px-4 py-3 text-right">Rp {{ number_format($prod->price, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-center font-bold text-gray-900 dark:text-white">{{ $prod->stock }}</td>
                                    <td class="px-4 py-3 text-right font-bold text-gray-900 dark:text-white">
                                        Rp {{ number_format($prod->stock * ($prod->price ?? 0), 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if($prod->stock <= 0)
                                            <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-rose-100 dark:bg-rose-950/50 text-rose-700 dark:text-rose-400 border border-rose-300 dark:border-rose-800">HABIS</span>
                                        @elseif($prod->stock <= 10)
                                            <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-100 dark:bg-amber-950/50 text-amber-700 dark:text-amber-400 border border-amber-300 dark:border-amber-800">MENIPIS</span>
                                        @else
                                            <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-100 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-400 border border-emerald-300 dark:border-emerald-800">TERSEDIA</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-8 text-center text-gray-400">
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
            <div class="p-4 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
                <div class="flex flex-wrap items-center gap-3">
                    <div class="flex items-center gap-2">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Rentang Waktu:</label>
                        <select
                            wire:model.live="bestsellerDays"
                            class="px-3 py-1.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs font-medium focus:ring-2 focus:ring-primary-500"
                        >
                            <option value="7">7 Hari Terakhir</option>
                            <option value="30">30 Hari Terakhir</option>
                            <option value="90">3 Bulan Terakhir</option>
                            <option value="365">1 Tahun Terakhir</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Tampilkan:</label>
                        <select
                            wire:model.live="bestsellerLimit"
                            class="px-3 py-1.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs font-medium focus:ring-2 focus:ring-primary-500"
                        >
                            <option value="5">Top 5</option>
                            <option value="10">Top 10</option>
                            <option value="25">Top 25</option>
                            <option value="50">Top 50</option>
                        </select>
                    </div>
                </div>

                <button
                    type="button"
                    wire:click="exportBestSellerCsv"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 active:scale-95 text-white font-semibold text-xs transition shadow-sm"
                >
                    <x-heroicon-m-arrow-down-tray class="w-4 h-4" />
                    <span>Ekspor Excel (.csv)</span>
                </button>
            </div>

            {{-- Table --}}
            <div class="rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-800">
                    <div class="font-bold text-sm text-gray-800 dark:text-gray-200">
                        Daftar Produk Terlaris (Periode {{ $bestsellerDays }} Hari Terakhir)
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-gray-700 dark:text-gray-300">
                        <thead class="bg-gray-50 dark:bg-gray-800/60 uppercase tracking-wider font-semibold text-gray-500 border-b border-gray-200 dark:border-gray-800">
                            <tr>
                                <th class="px-4 py-3 text-center" style="width: 50px;">Rank</th>
                                <th class="px-4 py-3">Nama Produk</th>
                                <th class="px-4 py-3 text-center">Total Terjual</th>
                                <th class="px-4 py-3 text-right">Total Nilai Penjualan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @forelse($bsData['items'] as $idx => $item)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition">
                                    <td class="px-4 py-3 text-center">
                                        @if($idx === 0)
                                            <span class="w-6 h-6 rounded-full bg-amber-400 text-slate-950 font-black inline-flex items-center justify-center text-xs shadow-sm">1</span>
                                        @elseif($idx === 1)
                                            <span class="w-6 h-6 rounded-full bg-slate-300 text-slate-900 font-black inline-flex items-center justify-center text-xs shadow-sm">2</span>
                                        @elseif($idx === 2)
                                            <span class="w-6 h-6 rounded-full bg-amber-600 text-white font-black inline-flex items-center justify-center text-xs shadow-sm">3</span>
                                        @else
                                            <span class="text-gray-500 font-semibold">{{ $idx + 1 }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">{{ $item->product_name }}</td>
                                    <td class="px-4 py-3 text-center font-extrabold text-indigo-600 dark:text-indigo-400">{{ $item->total_sold }} Unit</td>
                                    <td class="px-4 py-3 text-right font-bold text-gray-900 dark:text-white">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-gray-400">
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
            <div class="p-4 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Minimal Pesanan:</label>
                    <input
                        type="number"
                        min="1"
                        wire:model.live="customerMinOrders"
                        class="w-20 px-3 py-1.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs font-medium focus:ring-2 focus:ring-primary-500"
                    />
                </div>

                <button
                    type="button"
                    wire:click="exportCustomerCsv"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 active:scale-95 text-white font-semibold text-xs transition shadow-sm"
                >
                    <x-heroicon-m-arrow-down-tray class="w-4 h-4" />
                    <span>Ekspor Excel (.csv)</span>
                </button>
            </div>

            {{-- Table --}}
            <div class="rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-800">
                    <div class="font-bold text-sm text-gray-800 dark:text-gray-200">
                        Peringkat Pelanggan Berdasarkan Total Akumulasi Belanja
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-gray-700 dark:text-gray-300">
                        <thead class="bg-gray-50 dark:bg-gray-800/60 uppercase tracking-wider font-semibold text-gray-500 border-b border-gray-200 dark:border-gray-800">
                            <tr>
                                <th class="px-4 py-3 text-center" style="width: 50px;">No</th>
                                <th class="px-4 py-3">Nama Pelanggan</th>
                                <th class="px-4 py-3">Kontak & Email</th>
                                <th class="px-4 py-3 text-center">Jumlah Transaksi Lunas</th>
                                <th class="px-4 py-3 text-right">Total Akumulasi Belanja</th>
                                <th class="px-4 py-3 text-center">Transaksi Terakhir</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @forelse($custData['customers'] as $idx => $cust)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition">
                                    <td class="px-4 py-3 text-center text-gray-400">{{ $idx + 1 }}</td>
                                    <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">{{ $cust->shipping_name }}</td>
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-gray-800 dark:text-gray-200">{{ $cust->shipping_phone }}</div>
                                        <div class="text-[11px] text-gray-500">{{ $cust->shipping_email ?: '-' }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-center font-bold text-indigo-600 dark:text-indigo-400">{{ $cust->order_count }} Pesanan</td>
                                    <td class="px-4 py-3 text-right font-black text-gray-900 dark:text-white">Rp {{ number_format($cust->total_spent, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-center text-gray-500">{{ \Illuminate\Support\Carbon::parse($cust->last_order_at)->format('d/m/Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-gray-400">
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
