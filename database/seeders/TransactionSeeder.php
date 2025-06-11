<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transactions = [
            // Income transactions
            [
                'description' => 'Gaji Bulanan',
                'amount' => 8000000,
                'type' => 'income',
                'category' => 'Gaji',
                'transaction_date' => Carbon::now()->subDays(5),
            ],
            [
                'description' => 'Freelance Project',
                'amount' => 2500000,
                'type' => 'income',
                'category' => 'Freelance',
                'transaction_date' => Carbon::now()->subDays(10),
            ],
            [
                'description' => 'Bonus Kinerja',
                'amount' => 1500000,
                'type' => 'income',
                'category' => 'Bonus',
                'transaction_date' => Carbon::now()->subDays(15),
            ],
            
            // Expense transactions
            [
                'description' => 'Belanja Groceries',
                'amount' => 450000,
                'type' => 'expense',
                'category' => 'Makanan',
                'transaction_date' => Carbon::now()->subDays(2),
            ],
            [
                'description' => 'Bayar Listrik',
                'amount' => 350000,
                'type' => 'expense',
                'category' => 'Utilitas',
                'transaction_date' => Carbon::now()->subDays(3),
            ],
            [
                'description' => 'Bensin Motor',
                'amount' => 150000,
                'type' => 'expense',
                'category' => 'Transport',
                'transaction_date' => Carbon::now()->subDays(1),
            ],
            [
                'description' => 'Makan Siang',
                'amount' => 75000,
                'type' => 'expense',
                'category' => 'Makanan',
                'transaction_date' => Carbon::now(),
            ],
            [
                'description' => 'Subscription Netflix',
                'amount' => 54000,
                'type' => 'expense',
                'category' => 'Hiburan',
                'transaction_date' => Carbon::now()->subDays(7),
            ],
            [
                'description' => 'Bayar Internet',
                'amount' => 350000,
                'type' => 'expense',
                'category' => 'Utilitas',
                'transaction_date' => Carbon::now()->subDays(8),
            ],
            [
                'description' => 'Beli Buku',
                'amount' => 125000,
                'type' => 'expense',
                'category' => 'Pendidikan',
                'transaction_date' => Carbon::now()->subDays(4),
            ],
        ];

        foreach ($transactions as $transaction) {
            Transaction::create($transaction);
        }
    }
}