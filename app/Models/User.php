<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relacionamento com UserAccount (dados da conta)
    public function userAccount()
    {
        return $this->hasOne(UserAccount::class, 'user_id');
    }

    // Relacionamento com UserProfile (perfil do usuário)
    public function userProfile()
    {
        return $this->hasOne(UserProfile::class, 'user_id');
    }

    // Relacionamento com UserPersonalDetail (dados pessoais)
    public function userPersonalDetail()
    {
        return $this->hasOne(UserPersonalDetail::class, 'user_id');
    }

    // Relacionamento com UserDenomination (denominação religiosa)
    public function userDenomination()
    {
        return $this->hasOne(UserDenomination::class);
    }

    // Relacionamento com Endereço
    public function address()
    {
        return $this->hasOne(Address::class);
    }

    // Relacionamento com Interesses (Muitos para Muitos)
    public function interests()
    {
        return $this->belongsToMany(Interest::class, 'user_interests', 'user_id', 'interest_id');
    }

    // Relacionamento com Fotos (Um para Muitos)
    public function photos()
    {
        return $this->hasMany(Photo::class);
    }
}
