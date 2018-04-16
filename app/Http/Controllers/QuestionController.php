<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuestionRequest;
use App\Question;
use App\Topic;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
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
        $topic_list = $this->normalizeTopic($request->input('topic'));

        // create question
        $question_info = $request->toArray() + ['user_id' => \Auth::id()];
        $result_created = Question::create($question_info);

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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Question $question
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Question $question)
    {
        //
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

    /**
     *  对新增的话题进行处理
     * @param array $topics
     * @return array
     */
    protected function normalizeTopic(array $topics)
    {
        return collect($topics)->map(function($topic){
            if (is_numeric($topic)) {
                return (int)$topic;
            }
            $new_topic = Topic::create(['name' => $topic]);

            return  $new_topic['id'];
        })->toArray();
    }
}
