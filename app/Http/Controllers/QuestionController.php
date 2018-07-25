<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuestionRequest;
use App\Question;
use App\Repositories\QuestionSelfRepository;
use App\FollowerQuestion;

class QuestionController extends Controller
{

    protected $question_repositories;

    public function __construct(QuestionSelfRepository $question_repositories)
    {
        $this->question_repositories = $question_repositories;
    }

    /**
     * 判断当前用户是否关注了某个问题
     * @return \Illuminate\Http\JsonResponse
     */
    public function follower()
    {
        $question_id = request()->post('question_id');
        $user = \Auth::guard('api')->user();

        $is_followed = $user->followThisQuestion($question_id);

        return response()->json(['followed' => !!$is_followed, 'status' => 0]);
    }

    /**
     * 关注或者不再关注某个问题
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function toggle()
    {
        try {
            // 是否关注了某个问题
            $question_id = request()->post('question_id');
            $user_id = \Auth::guard('api')->id();

            $where = compact('question_id', 'user_id');
            $obj_followed = \App\FollowerQuestion::where($where)
                ->first();

            // 已经关注了 则删掉  && 关注者减一
            if ($obj_followed) {
                $obj_followed->delete();

                Question::find($question_id)->decrement('flowers_count', 1);
                return response()->json(['followed' => false, 'status' => 0]);
            }

            // 没有关注 则添加 && 关着者+1
            FollowerQuestion::create(compact('question_id', 'user_id'));
            Question::find($question_id)->increment('flowers_count', 1);
            return response()->json(['followed' => true, 'status' =>  0]);
        } catch (\Exception $e) {
            return response(['status' => 9999, 'msg' => $e->getMessage()]);
        }
    }


    /**
     * 当前问题下所有的评论
     */
    public function comments()
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $questions = $this->question_repositories->getQuestionsFeed();
        return view('questions.index')->with(compact('questions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('questions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(QuestionRequest $request)
    {
        $topic_list = $this->question_repositories->normalizeTopic($request->input('topic'));

        // create question
        $question_info = $request->toArray() + ['user_id' => \Auth::id()];
        $result_created = $this->question_repositories->create($question_info);

        // create relationship
        $result_created->topic()->attach($topic_list);
        return redirect('/Question/show/' . $result_created['id']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Question $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        $question = $this->question_repositories->byIdWithTopicsAndAnswers($question->id);
        return view('questions.show')->with(compact('question'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Question $question
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question)
    {
        // 不是作者的话 则跳回来源页
        if (\Auth::user()->owns($question)) {
            return view('questions.edit')->with(compact('question'));
        }
        return back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param QuestionRequest $request
     * @param  \App\Question $question
     * @return \Illuminate\Http\Response
     */
    public function update(QuestionRequest $request, Question $question)
    {
        $this->question_repositories->update($request, $question);
        return redirect('/Question/show/' . $question->id);
    }

    /**
     * Remove the specified resource from storage.
     * @param Question $question
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(Question $question)
    {


        if (\Auth::user()->owns($question)) {
            $question->topic()->sync([]);
            $question->delete();
            return redirect('/Question/index');
        }

        abort(403, '您没有权限删除');
    }
}
