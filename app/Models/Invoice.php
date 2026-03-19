<?php
// app/Models/Invoice.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_id',
        'invoice_number',
        'status',
        'total',
        'issue_date',
        'due_date',
        'notes',
        'public_token',
    ];

    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'due_date' => 'date',
            'total' => 'decimal:2',
        ];
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    // Helpers
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'paid' => 'badge bg-success',
            'draft' => 'badge bg-secondary',
            default => 'badge bg-light',
        };
    }

    // Auto-generate invoice number before creating
    protected static function booted(): void
    {
        static::creating(function (Invoice $invoice) {
            $invoice->invoice_number ??= static::generateNumber($invoice->user_id);
        });
    }


    public static function generateNumber(int $userId): string
    {
        $prefix = \App\Models\Setting::where('user_id', $userId)
            ->value('invoice_prefix') ?? 'INV';

        $count = static::where('user_id', $userId)->count() + 1;

        return $prefix . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
    public function generatePublicToken(): string
    {
        $token = bin2hex(random_bytes(32)); // 64 char hex
        $this->update(['public_token' => $token]);
        return $token;
    }
    // رابط الصفحة العامة
    public function publicUrl(): ?string
    {
        return $this->public_token
            ? route('invoices.public', $this->public_token)
            : null;
    }

    // هل الفاتورة عامة؟
    public function isPublic(): bool
    {
        return !is_null($this->public_token);
    }

}