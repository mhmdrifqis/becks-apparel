<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'midtrans_server_key',
        'midtrans_client_key',
        'is_production',
    ];

    protected $casts = [
        'midtrans_server_key' => 'encrypted',
        'is_production' => 'boolean',
    ];
}
