<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecentTrack extends Model
{
    use HasFactory;
    
    protected $fillable = ['spotify_id', 'user_id', 'name', 'artist', 'played_at'];

    protected $casts = [
        'played_at' => 'datetime',
    ];
}