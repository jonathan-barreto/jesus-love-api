<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLike extends Model
{
    use HasFactory;

    protected $fillable = ['user_who_liked', 'user_liked', 'like_type_id'];

    public function userWhoLiked()
    {
        return $this->belongsTo(User::class, 'user_who_liked');
    }

    public function userLiked()
    {
        return $this->belongsTo(User::class, 'user_liked');
    }

    public function likeType()
    {
        return $this->belongsTo(LikeType::class);
    }
}
