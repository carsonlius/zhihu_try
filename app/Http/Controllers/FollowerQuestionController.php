<?php

namespace App\Http\Controllers;

use App\Question;
use App\Repositories\FollowerQuestionRepository;
use Illuminate\Http\Request;

class FollowerQuestionController extends Controller
{
    protected $follower_repository;

    public function __construct(FollowerQuestionRepository $followerQuestionRepository)
    {
        $this->follower_repository = $followerQuestionRepository;
    }

    /**
     * 关注问题
     * @param Question $question
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Question $question)
    {
        $this->follower_repository->store($question);
        return back();
    }

}
