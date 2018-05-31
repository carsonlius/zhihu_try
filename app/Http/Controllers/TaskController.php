<?php

namespace App\Http\Controllers;

use App\Task;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = Task::latest()->get()->map(function ($item){
            $item['title'] = $item['name'];
            return $item;
        });
        return \response()->json($response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $task = Task::create($request->all());
            $status = 'success';
        } catch (\Exception $e) {
            $status = 'error';
        }
        return response()->json(compact('status', 'task'));
    }

    /**
     * Display the specified resource.
     * @param Task $task
     * @return Task
     */
    public function show(Task $task)
    {
        $task['title'] = $task['name'];
        $task['computed'] = !! mt_rand(0,1);
        return $task;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {

        $task->computed = $request->get('computed');
        $success = $task->save();

        $msg = $success ? '更新成功' : '更新失败';
        return compact('msg', 'success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        try {
            $result = $task->delete();
            $status = 'success';
        } catch (\Exception $e) {
            $status = 'error';
        }

        return response()->json(compact('result', 'status'));
    }
}
