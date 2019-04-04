<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class IndexController extends Controller
{
    public function index(){
        // 分类
        $cateInfo = DB::table('shop_category')->where(['pid'=>0])->get();

        // 取值
        $session = Session::get('user_name');
        // 商品路径
        $path = config('app.path');
        $goodsInfo1 = DB::table('shop_goods')
            ->where('is_new',1)
            ->limit(40)
            ->get();

        $goodsInfo2 = DB::table('shop_goods')
            ->orderBy('goods_num','desc')
            ->limit(10)
            ->get();

        return view('index',compact('goodsInfo1','goodsInfo2','path','session','cateInfo'));
    }

}
