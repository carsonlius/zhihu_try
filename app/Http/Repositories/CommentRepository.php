<?php

namespace App\Http\Repositories;

use App\Answer;
use App\Comment;
use App\Question;
use function Symfony\Component\Debug\Tests\testHeader;

class CommentRepository
{

    /**
     * 获取某个问题下面所有的评论
     * @param $question_id
     * @return Question|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null|object
     */
    public function question($question_id)
    {
        return Question::find($question_id)->comments;
    }

    /**
     * 获取某个回答下面所有的评论
     * @param integer $answer_id
     * @return Answer|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null|object
     */
    public function answer($answer_id)
    {
        return Question::find($answer_id)->comments;
    }

    /**
     * 存储数据
     * @throws \Exception
     */
    public function store()
    {
        // 检查参数是否合法
        $this->checkParams();

        // 生成参数
        $params = $this->genParams();

        // 存储
        return Comment::create($params);
    }

    /**
     * 生成参数
     * @return array
     */
    protected function genParams()
    {
        $commentable_id = request()->post('id', '');
        $type = request()->post('type', '');
        $commentable_type = $type == 'question' ? 'App\Question' : 'App\Answer';
        $user_id = \Auth::guard('api')->id();
        $body = request()->post('body', '');

        return compact('commentable_id', 'commentable_type', 'user_id', 'body');
    }

    /**
     * 检查参数是否合法
     * @throws \Exception
     */
    protected function checkParams()
    {
        $commentale_id = request()->post('id', '');
        if (!$commentale_id) {
            throw new \Exception('缺少commentable_id，请重试');
        }
        $type = request()->post('type', '');
        if (!$type) {
            throw new \Exception('缺少commentable_type，请重试');
        }
        $body = request()->post('body', '');
        if (!$body) {
            throw new \Exception('请填写评论内容');
        }
    }
}