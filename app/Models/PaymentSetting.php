<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Crypt;

class PaymentSetting extends Model
{
    use HasFactory;

    protected $table = 'payment_settings';

    protected $fillable = [
        'is_active',
        'environment',
        'sandbox_api_key',
        'production_api_key',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Accessor for sandbox_api_key with safe decryption fallback
     */
    public function getSandboxApiKeyAttribute($value)
    {
        if (empty($value)) {
            return $value;
        }
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value;
        }
    }

    /**
     * Mutator for sandbox_api_key with safe encryption
     */
    public function setSandboxApiKeyAttribute($value)
    {
        if (!empty($value)) {
            try {
                $this->attributes['sandbox_api_key'] = Crypt::encryptString($value);
            } catch (\Exception $e) {
                $this->attributes['sandbox_api_key'] = $value;
            }
        } else {
            $this->attributes['sandbox_api_key'] = null;
        }
    }

    /**
     * Accessor for production_api_key with safe decryption fallback
     */
    public function getProductionApiKeyAttribute($value)
    {
        if (empty($value)) {
            return $value;
        }
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value;
        }
    }

    /**
     * Mutator for production_api_key with safe encryption
     */
    public function setProductionApiKeyAttribute($value)
    {
        if (!empty($value)) {
            try {
                $this->attributes['production_api_key'] = Crypt::encryptString($value);
            } catch (\Exception $e) {
                $this->attributes['production_api_key'] = $value;
            }
        } else {
            $this->attributes['production_api_key'] = null;
        }
    }
}
