<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPersonalDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'marital_status_id',
        'children_preference_id',
        'education_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function education()
    {
        return $this->belongsTo(Education::class);
    }

    public function maritalStatus()
    {
        return $this->belongsTo(MaritalStatus::class);
    }

    public function childrenPreference()
    {
        return $this->belongsTo(ChildrenPreference::class);
    }
}
