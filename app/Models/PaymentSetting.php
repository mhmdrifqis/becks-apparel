<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_active',
        'environment',
        'sandbox_api_key',
        'production_api_key',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sandbox_api_key' => 'encrypted',
        'production_api_key' => 'encrypted',
    ];
}
