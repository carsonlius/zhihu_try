<?php

namespace App\Collection;

use Illuminate\Support\Collection;

class MessageCollection extends Collection
{
    public function markAsRead()
    {
        $this->each(function($model){
            $model->markAsRead();
        });
    }
}