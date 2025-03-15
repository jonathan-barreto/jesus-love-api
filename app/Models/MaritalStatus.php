<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaritalStatus extends Model
{
    use HasFactory;

    protected $fillable = ['status'];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    // Relacionamento com UserPersonalDetail
    public function userPersonalDetails()
    {
        return $this->hasMany(UserPersonalDetail::class, 'marital_status_id');
    }
}
