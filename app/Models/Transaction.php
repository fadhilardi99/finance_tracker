<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'amount', 
        'type',
        'category',
        'transaction_date'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date',
    ];

    // Scope untuk filter berdasarkan tipe
    public function scopeIncome($query)
    {
        return $query->where('type', 'income');
    }

    public function scopeExpense($query)
    {
        return $query->where('type', 'expense');
    }

    // Scope untuk filter berdasarkan tanggal
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('transaction_date', [$startDate, $endDate]);
    }

    // Accessor untuk format mata uang
    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    // Accessor untuk format tanggal Indonesia
    public function getFormattedDateAttribute()
    {
        return Carbon::parse($this->transaction_date)->format('d M Y');
    }

    // Method untuk mendapatkan warna badge berdasarkan tipe
    public function getTypeBadgeColorAttribute()
    {
        return $this->type === 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
    }

    // Method untuk mendapatkan icon berdasarkan tipe
    public function getTypeIconAttribute()
    {
        return $this->type === 'income' ? '↗️' : '↘️';
    }
}