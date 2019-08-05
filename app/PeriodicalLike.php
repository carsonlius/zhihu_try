<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PeriodicalLike extends Model
{
    protected $fillable = [
        'periodical_id',
        'user_id'
    ];
}
