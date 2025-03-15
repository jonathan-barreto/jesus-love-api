<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildrenPreference extends Model
{
    use HasFactory;

    protected $fillable = ['preference'];

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
        return $this->hasMany(UserPersonalDetail::class, 'children_preference_id');
    }
}
