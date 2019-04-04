<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MeltController extends Controller
{
    // 分销添加
    public function joinmelt(){
        return view('melt/joinmelt');
    }

    // 分销展示
    public function melt(){
        return view('melt/melt');
    }


}
