<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    //
    protected $fillable = [
        'name_id',
        'twitch_id',
        'youtube_id',
        'x_id',
    ];
}
