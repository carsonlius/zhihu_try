<?php

namespace App\Http\Repositories;

use App\Answer;
use App\Comment;
use App\Question;

class CommentRepository
{
    /**
     * 获取某个问题下面所有的评论
     * @param int $id
     * @return Question|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null|object
     */
    public function question($id)
    {
        $question_info = Question::with('comments', 'comments.user')->where(compact('id'))->first();
        return $question_info->comments;
    }

    /**
     * 获取某个回答下面所有的评论
     * @param integer $id 传入的答案ID
     * @return Answer|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null|object
     */
    public function answer($id)
    {
        $answer = Answer::with('comments', 'comments.user')->where(compact('id'))->first();
        return $answer->comments;
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
        $comment_store = Comment::create($params);

        // return 带有user预加载的信息
        return Comment::with('user')->where('id', $comment_store->id)->first();
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