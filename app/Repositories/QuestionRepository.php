<?php

namespace App\Repositories;

use App\Http\Requests\QuestionRequest;
use App\Question;
use App\Topic;

class QuestionRepository
{
    /**
     * 根据id获取包含topic多对多关系的信息
     * @param $id
     * @return mixed
     */
    public function byIdWithTopics($id)
    {
        return Question::where(compact('id'))->with('topic')->first();
    }

    /**
     * 创建新的问题
     * @param array $params
     * @return mixed
     */
    public function create(array $params)
    {
        return Question::create($params);
    }

    /**
     *  对新增的话题进行处理
     * @param array $topics
     * @return array
     */
    public function normalizeTopic(array $topics)
    {
        $ids = Topic::pluck('id');
        return collect($topics)->map(function ($topic) use ($ids) {

            // 如果传递过来的是id
            if (ctype_digit($topic) && $ids->contains($topic)) {
                return (int)$topic;
            }

            return Topic::firstOrCreate(['name' => $topic])->id;
        })->toArray();
    }

    public function update(QuestionRequest $request, Question $question)
    {
        $question->update($request->toArray());
        $topic_list = $this->normalizeTopic($request->get('topic'));
        $question->topic()->sync($topic_list);
    }
}