<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Transaction;
use Carbon\Carbon;
use Livewire\Attributes\Validate;

class FinanceTracker extends Component
{
    // Form properties
    #[Validate('required|string|max:255')]
    public $description = '';
    
    #[Validate('required|numeric|min:0.01')]
    public $amount = '';
    
    #[Validate('required|in:income,expense')]
    public $type = 'income';
    
    #[Validate('nullable|string|max:100')]
    public $category = '';
    
    #[Validate('required|date')]
    public $transaction_date = '';

    // Filter properties
    public $filter_start_date = '';
    public $filter_end_date = '';
    public $filter_type = '';

    // State management
    public $transactions = [];
    public $total_balance = 0;
    public $total_income = 0;
    public $total_expense = 0;
    public $success_message = '';
    public $edit_mode = false;
    public $edit_transaction_id = null;

    // Chart data
    public $chart_data = [];

    public function mount()
    {
        $this->transaction_date = Carbon::today()->format('Y-m-d');
        $this->filter_start_date = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->filter_end_date = Carbon::now()->endOfMonth()->format('Y-m-d');
        $this->loadTransactions();
        $this->calculateSummary();
        $this->prepareChartData();
    }

    public function rules()
    {
        return [
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:income,expense',
            'category' => 'nullable|string|max:100',
            'transaction_date' => 'required|date',
        ];
    }

    public function loadTransactions()
    {
        $query = Transaction::query()->orderBy('transaction_date', 'desc')->orderBy('created_at', 'desc');

        // Apply date filter
        if ($this->filter_start_date && $this->filter_end_date) {
            $query->dateRange($this->filter_start_date, $this->filter_end_date);
        }

        // Apply type filter
        if ($this->filter_type) {
            $query->where('type', $this->filter_type);
        }

        $this->transactions = $query->get()->toArray();
    }

    public function calculateSummary()
    {
        $query = Transaction::query();

        // Apply same filters as transaction list
        if ($this->filter_start_date && $this->filter_end_date) {
            $query->dateRange($this->filter_start_date, $this->filter_end_date);
        }

        $this->total_income = $query->clone()->income()->sum('amount');
        $this->total_expense = $query->clone()->expense()->sum('amount');
        $this->total_balance = $this->total_income - $this->total_expense;
    }

    public function prepareChartData()
    {
        // Data untuk chart berdasarkan periode filter
        $query = Transaction::query();
        
        if ($this->filter_start_date && $this->filter_end_date) {
            $query->dateRange($this->filter_start_date, $this->filter_end_date);
        }

        // Group by date for line chart
        $daily_data = $query->clone()
            ->selectRaw('DATE(transaction_date) as date, type, SUM(amount) as total')
            ->groupBy('date', 'type')
            ->orderBy('date')
            ->get();

        $dates = [];
        $income_data = [];
        $expense_data = [];

        foreach ($daily_data as $data) {
            $date = Carbon::parse($data->date)->format('M d');
            if (!in_array($date, $dates)) {
                $dates[] = $date;
            }
        }

        // Initialize arrays
        foreach ($dates as $date) {
            $income_data[$date] = 0;
            $expense_data[$date] = 0;
        }

        // Fill data
        foreach ($daily_data as $data) {
            $date = Carbon::parse($data->date)->format('M d');
            if ($data->type === 'income') {
                $income_data[$date] = $data->total;
            } else {
                $expense_data[$date] = $data->total;
            }
        }

        $this->chart_data = [
            'labels' => array_values($dates),
            'income' => array_values($income_data),
            'expense' => array_values($expense_data)
        ];
    }

    public function addTransaction()
    {
        $this->validate();

        try {
            if ($this->edit_mode && $this->edit_transaction_id) {
                // Update existing transaction
                $transaction = Transaction::findOrFail($this->edit_transaction_id);
                $transaction->update([
                    'description' => $this->description,
                    'amount' => $this->amount,
                    'type' => $this->type,
                    'category' => $this->category,
                    'transaction_date' => $this->transaction_date,
                ]);
                $this->success_message = 'Transaksi berhasil diperbarui!';
                $this->edit_mode = false;
                $this->edit_transaction_id = null;
            } else {
                // Create new transaction
                Transaction::create([
                    'description' => $this->description,
                    'amount' => $this->amount,
                    'type' => $this->type,
                    'category' => $this->category,
                    'transaction_date' => $this->transaction_date,
                ]);
                $this->success_message = 'Transaksi berhasil ditambahkan!';
            }

            $this->resetForm();
            $this->loadTransactions();
            $this->calculateSummary();
            $this->prepareChartData();

            // Auto hide success message after 3 seconds
            $this->dispatch('show-success');

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function editTransaction($id)
    {
        $transaction = Transaction::findOrFail($id);
        
        $this->edit_mode = true;
        $this->edit_transaction_id = $id;
        $this->description = $transaction->description;
        $this->amount = $transaction->amount;
        $this->type = $transaction->type;
        $this->category = $transaction->category;
        $this->transaction_date = $transaction->transaction_date->format('Y-m-d');
    }

    public function cancelEdit()
    {
        $this->edit_mode = false;
        $this->edit_transaction_id = null;
        $this->resetForm();
    }

    public function deleteTransaction($id)
    {
        try {
            Transaction::findOrFail($id)->delete();
            $this->success_message = 'Transaksi berhasil dihapus!';
            
            $this->loadTransactions();
            $this->calculateSummary();
            $this->prepareChartData();
            
            $this->dispatch('show-success');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function applyFilters()
    {
        $this->loadTransactions();
        $this->calculateSummary();
        $this->prepareChartData();
    }

    public function resetFilters()
    {
        $this->filter_start_date = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->filter_end_date = Carbon::now()->endOfMonth()->format('Y-m-d');
        $this->filter_type = '';
        $this->applyFilters();
    }

    private function resetForm()
    {
        $this->description = '';
        $this->amount = '';
        $this->type = 'income';
        $this->category = '';
        $this->transaction_date = Carbon::today()->format('Y-m-d');
    }

    public function render()
    {
        return view('livewire.finance-tracker');
    }
}