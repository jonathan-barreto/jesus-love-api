<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone_number',
        'date_of_birth',
        'gender',
        'bio',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    // Relacionamento com o usuário
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
