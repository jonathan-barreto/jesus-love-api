<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LikeType extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function likes()
    {
        return $this->hasMany(UserLike::class);
    }
}
