<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuestionRequest;
use App\Question;
use App\Repositories\QuestionRepository;
use Illuminate\Http\Request;

class QuestionController extends Controller
{

    protected $question_repositories;

    public function __construct(QuestionRepository $question_repositories)
    {
        $this->question_repositories = $question_repositories;
        $this->middleware('auth')->except('index', 'show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        dump('列表页');
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
        $question = $this->question_repositories->byIdWithTopics($question->id);
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
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Question $question
     * @return \Illuminate\Http\Response
     */
    public function update(QuestionRequest $request, Question $question)
    {
        $this->update($request, $question);
        return redirect('/Question/show/' . $question->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Question $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        //

    }
}
