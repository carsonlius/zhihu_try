<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Repositories\CommentRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected $repository_comment;

    public function __construct(CommentRepository $repository)
    {
        $this->repository_comment = $repository;
    }

    /**
     * 某个问题下面的评论列表
     * @param integer $question_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function question($question_id)
    {
        try {
            $list_comments = $this->repository_comment->question($question_id);
            $status = 0;

            return response()->json(compact('list_comments', 'status'));
        } catch (\Exception $e) {
            $status = 4198;
            $msg = $e->getMessage();
            return response()->json(compact('status', 'msg'));
        }
    }

    /**
     * 某个回答下面的评论列表
     * @param integer $answer_id 答案的ID
     * @return JsonResponse
     */
    public function answer($answer_id)
    {
        try {
            $list_comments = $this->repository_comment->answer($answer_id);
            $status = 0;

            return response()->json(compact('list_comments', 'status'));
        } catch (\Exception $e) {
            $status = 4198;
            $msg = $e->getMessage();
            return response()->json(compact('status', 'msg'));
        }

    }

    /**
     * 存储评论
     */
    public function store()
    {
        try {
            $result_store = $this->repository_comment->store();
            $status = $result_store ? 0 : 478 ;
            $msg = '存储成功';
            return response()->json(compact('status', 'msg', 'result_store'));

        } catch (\Exception $e) {
            $status = 478;
            $msg = $e->getMessage();
            return response()->json(compact('status', 'msg'));
        }
    }
}
