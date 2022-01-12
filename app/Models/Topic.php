<?php

namespace App\Models;

use App\Models\Post;
use App\Models\Traits\OrderableTrait;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Topic extends Model
{
    use HasFactory;
    use OrderableTrait;

    protected $fillable = ['title'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function posts() {
        return $this->hasMany(Post::class);
    }
}
