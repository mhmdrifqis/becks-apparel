<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'user_id',
        'package_id',
        'material_id',
        'quantity',
        'roster'
    ];

    protected $casts = [
        'roster' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
