<?php

namespace App;

use App\Events\QuestionCreatedEvent;
use Illuminate\Database\Eloquent\Model;
use Fico7489\Laravel\Pivot\Traits\PivotEventTrait;

class Question extends Model
{
    use PivotEventTrait; // 解决sync不触发事件的问题

    protected $fillable = ['title', 'user_id', 'body', 'flowers_count', 'comments_count', 'close_comment', 'is_hidden'];

    public function isHidden()
    {
        return $this->is_hidden === 'T';
    }

    public function isClose()
    {
        return $this->close_comment = 'T';
    }

    protected $dispatchesEvents = [
        'created' => QuestionCreatedEvent::class
    ];

    public function topic()
    {
        return $this->belongsToMany(Topic::class)->withTimestamps();
    }

    public static function boot()
    {
        parent::boot();

        static::pivotAttaching(function ($model, $relationName, $pivotIds, $pivotIdsAttributes) {
            //
        });

        static::pivotAttached(function ($model, $relationName, $pivotIds, $pivotIdsAttributes) {
            // 因为触发 所以$pivotIds一定是有值的
            array_map(function($topic_id){
                Topic::find($topic_id)->increment('questions_count', 1);
            }, $pivotIds);
        });

        static::pivotDetaching(function ($model, $relationName, $pivotIds) {

        });

        // 当问题关联的话题被detached 话题下面的问题数量自然要减去一个
        static::pivotDetached(function ($model, $relationName, $pivotIds) {
            // 因为触发 所以$pivotIds一定是有值的
            array_map(function($topic_id){
                Topic::find($topic_id)->increment('questions_count', -1);
            }, $pivotIds);
        });

        static::pivotUpdating(function ($model, $relationName, $pivotIds, $pivotIdsAttributes) {
            //
        });

        static::pivotUpdated(function ($model, $relationName, $pivotIds, $pivotIdsAttributes) {
            //
        });

        static::updating(function ($model) {
            //this is how we catch standard eloquent events
        });
    }
}
