<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Http\Repositories\VoteRepository;

class VoteController extends Controller
{
    protected $repository_vote;
    public function __construct(VoteRepository $repository_vote)
    {
        $this->repository_vote = $repository_vote;
    }

    /**
     * 当前用户是否点赞了某个产品
     * @param $answer_id
     * @return array
     */
    public function users($answer_id)
    {
        try {
            // 是否点赞了某答案
            $voted = $this->repository_vote->hasVoteFor($answer_id);
            $status = 0;
            return response()->json(compact('status', 'voted'));
        } catch (\Exception $e) {
            $status = 7777;
            $msg = $e->getMessage();
            return response()->json(compact('status', 'msg'));
        }
    }

    /**
     * 当前用户进行点赞或者取消点赞的操作
     * @return array
     */
    public function vote()
    {
        try {
            $voted = $this->repository_vote->voteToggle();
            $status = 0;
            return response()->json(compact('status', 'voted'));

        } catch (\Exception $e) {
            $status = 7777;
            $msg = $e->getMessage();
            return response()->json(compact('status', 'msg'));
        }

    }
}
