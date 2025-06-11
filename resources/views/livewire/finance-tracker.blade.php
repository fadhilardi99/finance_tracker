<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Finance Tracker</h1>
            <p class="text-gray-600">Kelola keuangan Anda dengan mudah dan real-time</p>
        </div>

        <!-- Success Message -->
        @if($success_message)
            <div x-data="{ show: true }" 
                 x-show="show" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100 transform translate-y-0"
                 x-transition:leave-end="opacity-0 transform translate-y-2"
                 @show-success.window="show = true; setTimeout(() => show = false, 3000)"
                 class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                <span class="block sm:inline">{{ $success_message }}</span>
                <span @click="show = false" class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer">
                    <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                    </svg>
                </span>
            </div>
        @endif

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Balance -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                <span class="text-white font-bold">💰</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">Saldo Total</h3>
                            <p class="text-2xl font-bold {{ $total_balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                Rp {{ number_format($total_balance, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Income -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                <span class="text-white font-bold">↗️</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">Total Pemasukan</h3>
                            <p class="text-2xl font-bold text-green-600">
                                Rp {{ number_format($total_income, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Expense -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                                <span class="text-white font-bold">↘️</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">Total Pengeluaran</h3>
                            <p class="text-2xl font-bold text-red-600">
                                Rp {{ number_format($total_expense, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Transaction Form -->
            <div class="bg-white shadow-lg rounded-lg">
                <div class="p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">
                        {{ $edit_mode ? 'Edit Transaksi' : 'Tambah Transaksi' }}
                    </h2>
                    
                    <form wire:submit="addTransaction" class="space-y-4">
                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                                Deskripsi *
                            </label>
                            <input type="text" 
                                   wire:model="description" 
                                   id="description"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Contoh: Gaji bulanan, Beli makanan, dll">
                            @error('description') 
                                <span class="text-red-500 text-sm">{{ $message }}</span> 
                            @enderror
                        </div>

                        <!-- Amount -->
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">
                                Jumlah (Rp) *
                            </label>
                            <input type="number" 
                                   wire:model="amount" 
                                   id="amount"
                                   step="0.01"
                                   min="0.01"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="0.00">
                            @error('amount') 
                                <span class="text-red-500 text-sm">{{ $message }}</span> 
                            @enderror
                        </div>

                        <!-- Type -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">
                                Tipe *
                            </label>
                            <select wire:model="type" 
                                    id="type"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="income">Pemasukan</option>
                                <option value="expense">Pengeluaran</option>
                            </select>
                            @error('type') 
                                <span class="text-red-500 text-sm">{{ $message }}</span> 
                            @enderror
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">
                                Kategori
                            </label>
                            <input type="text" 
                                   wire:model="category" 
                                   id="category"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Contoh: Makanan, Transport, Gaji, dll">
                            @error('category') 
                                <span class="text-red-500 text-sm">{{ $message }}</span> 
                            @enderror
                        </div>

                        <!-- Transaction Date -->
                        <div>
                            <label for="transaction_date" class="block text-sm font-medium text-gray-700 mb-1">
                                Tanggal Transaksi *
                            </label>
                            <input type="date" 
                                   wire:model="transaction_date" 
                                   id="transaction_date"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('transaction_date') 
                                <span class="text-red-500 text-sm">{{ $message }}</span> 
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="flex gap-2 pt-4">
                            <button type="submit" 
                                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-200">
                                {{ $edit_mode ? 'Update Transaksi' : 'Tambah Transaksi' }}
                            </button>
                            
                            @if($edit_mode)
                                <button type="button" 
                                        wire:click="cancelEdit"
                                        class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md transition duration-200">
                                    Batal
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Transaction List -->
            <div class="bg-white shadow-lg rounded-lg">
                <div class="p-4 sm:p-6">
                    <!-- Header and Filters -->
                    <div class="mb-6">
                        <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-4">Daftar Transaksi</h2>
                        
                        <!-- Filters -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                            <!-- Date Range -->
                            <div class="col-span-1 sm:col-span-2 lg:col-span-2">
                                <div class="flex gap-2">
                                    <div class="flex-1">
                                        <label class="block text-xs text-gray-500 mb-1">Dari Tanggal</label>
                                        <input type="date" 
                                               wire:model="filter_start_date" 
                                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500">
                                    </div>
                                    <div class="flex-1">
                                        <label class="block text-xs text-gray-500 mb-1">Sampai Tanggal</label>
                                        <input type="date" 
                                               wire:model="filter_end_date" 
                                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500">
                                    </div>
                                </div>
                            </div>

                            <!-- Type Filter -->
                            <div class="col-span-1">
                                <label class="block text-xs text-gray-500 mb-1">Tipe Transaksi</label>
                                <select wire:model="filter_type" 
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500">
                                    <option value="">Semua Tipe</option>
                                    <option value="income">Pemasukan</option>
                                    <option value="expense">Pengeluaran</option>
                                </select>
                            </div>

                            <!-- Action Buttons -->
                            <div class="col-span-1 flex gap-2 items-end">
                                <button wire:click="applyFilters" 
                                        class="flex-1 px-3 py-2 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200 flex items-center justify-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                    </svg>
                                    Filter
                                </button>
                                <button wire:click="resetFilters" 
                                        class="flex-1 px-3 py-2 text-sm bg-gray-500 text-white rounded-md hover:bg-gray-600 transition duration-200 flex items-center justify-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Reset
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Transactions -->
                    <div class="space-y-3 max-h-[600px] overflow-y-auto pr-2">
                        @forelse($transactions as $transaction)
                            <div class="bg-white border border-gray-200 rounded-lg p-3 sm:p-4 hover:shadow-md transition duration-200">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center {{ $transaction['type'] === 'income' ? 'bg-green-100' : 'bg-red-100' }}">
                                                <span class="text-base sm:text-lg">
                                                    {{ $transaction['type'] === 'income' ? '↗️' : '↘️' }}
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <h4 class="font-medium text-gray-900 truncate">{{ $transaction['description'] }}</h4>
                                                <div class="flex items-center gap-2 mt-0.5">
                                                    <span class="text-xs sm:text-sm text-gray-500">
                                                        {{ \Carbon\Carbon::parse($transaction['transaction_date'])->format('d M Y') }}
                                                    </span>
                                                    @if($transaction['category'])
                                                        <span class="text-xs sm:text-sm text-gray-500">•</span>
                                                        <span class="text-xs sm:text-sm text-gray-500 truncate">{{ $transaction['category'] }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 ml-4">
                                        <span class="text-base sm:text-lg font-semibold whitespace-nowrap {{ $transaction['type'] === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $transaction['type'] === 'income' ? '+' : '-' }} Rp {{ number_format($transaction['amount'], 0, ',', '.') }}
                                        </span>
                                        <div class="flex gap-1">
                                            <button wire:click="editTransaction({{ $transaction['id'] }})"
                                                    class="p-1.5 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-md transition duration-200">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <button wire:click="deleteTransaction({{ $transaction['id'] }})"
                                                    class="p-1.5 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-md transition duration-200">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada transaksi</h3>
                                <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan transaksi baru.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>