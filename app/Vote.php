<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\VarDumper\Caster\ClassStub;

class Vote extends Model
{
    protected $fillable = ['answer_id', 'user_id'];
}
