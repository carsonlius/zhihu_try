<?php

namespace App\Http\Repositories;

class VoteRepository
{
    /**
     * 当前用户是否已经对某个问题点赞
     * @param $answer_id
     * @return boolean
     */
    public function hasVoteFor($answer_id)
    {
        return \Auth::guard('api')->user()->votesAnswer->contains('id', $answer_id);
    }

    /**
     * 当前用户toggle某个回答
     * @return boolean
     */
    public function voteToggle()
    {
        $answer_id = request()->post('answer_id');
        $response = \Auth::guard('api')->user()->votesAnswer()->toggle($answer_id);

        // 如果执行的是attached,则返回true；否则 false
        return count($response['attached']) ? true : false;
    }
}