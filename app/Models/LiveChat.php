<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveChat extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'user_name', 'status'];

    public function messages()
    {
        return $this->hasMany(LiveChatMessage::class);
    }
}
