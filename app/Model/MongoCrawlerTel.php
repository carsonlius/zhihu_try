<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class MongoCrawlerTel extends Model
{
    protected $connection = 'mongodb_backend';

    protected $collection = 'crawler_tels';

    protected $guarded = [];
}