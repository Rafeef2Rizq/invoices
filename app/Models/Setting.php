<?php
// app/Models/Setting.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'user_id',
        'company_name',
        'company_email',
        'company_phone',
        'company_address',
        'company_logo',
        'invoice_color',
        'invoice_prefix',
        'invoice_footer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper — get logo URL or null
    public function logoUrl(): ?string
    {
        return $this->company_logo
            ? asset('storage/' . $this->company_logo)
            : null;
    }
}