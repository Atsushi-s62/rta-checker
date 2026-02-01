<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Apply extends Model
{
    //
    protected $fillable = [
        'name_id',
        'twitch_id',
        'youtube_id',
        'x_id',
    ];
}
