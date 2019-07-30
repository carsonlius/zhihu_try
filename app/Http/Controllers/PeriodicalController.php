<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PeriodicalController extends Controller
{

    /**
     * 期刊列表
     */
    public function index()
    {
        return view('periodical.list');
    }

    /**
     * 新建期刊
     */
    public function create()
    {
        return view('periodical.create');
    }
}
