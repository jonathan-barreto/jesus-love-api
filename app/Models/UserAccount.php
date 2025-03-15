<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'is_active',
        'accepted_terms',
        'is_subscriber',
        'visibility',
        'last_login',
        'device_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'accepted_terms' => 'boolean',
        'is_subscriber' => 'boolean',
        'last_login' => 'datetime',
    ];

    // Relacionamento com o usuário
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
