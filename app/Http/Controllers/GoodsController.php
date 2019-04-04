<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class GoodsController extends Controller
{
    // 全部商品
    public function goodsList(){
        // 商品路径
        $path = config('app.path');

        // 根据条件查数据
        $cate_id = request()->id;
        if(!empty($cate_id)){
            Session::put(['cate_id'=>$cate_id]);
            $cateInfo = DB::table('shop_category')->get();
            if(!empty($cateInfo)){

                // 把对象处理成数组进行遍历
                foreach($cateInfo as $k=>$v){
                    $cateInfo[$k]=(array)$v;
                }

                // 无限级分类获取id
                $c_id = $this->getCateId($cateInfo,$cate_id);
                $goodsInfo = DB::table('shop_goods')->where(["is_new" => 1])->whereIn('cate_id',$c_id)->get();
            }
        }else{
            Session::forget('cate_id');
            $goodsInfo = DB::table('shop_goods')->where(["is_new" => 1])->get();
        }
        return view('goods.goodsList',['goodsInfo' => $goodsInfo,'path'=>$path]);
    }

    // 分类信息id
    public function getCateId($cateInfo,$pid){
        static $info = [];
        foreach($cateInfo as $k=>$v){
            if($v['pid'] == $pid){
                $info[] = $v['cate_id'];
                $this->getCateId($cateInfo,$v['cate_id']);
            }
        }
        return $info;
    }

    // 重新获取商品信息
    public function getGoodsInfo(){
        $cate_id = Session::get('cate_id');
        $field = request()->field;
        if(!empty($cate_id)){
            $cateInfo = DB::table('shop_category')->get();
            if(!empty($cateInfo)){

                // 把对象处理成数组进行遍历
                foreach($cateInfo as $k=>$v){
                    $cateInfo[$k]=(array)$v;
                }

                // 无限级分类获取id
                $c_id = $this->getCateId($cateInfo,$cate_id);
            }
        }
        if(!empty($cate_id) && $field == 'is_new'){
            $goodsInfo = DB::table('shop_goods')->where("is_new",1)->whereIn('cate_id',$c_id)->get();
        }else if(!empty($cate_id) && $field != 'is_new'){
            $goodsInfo = DB::table('shop_goods')->orderBy($field,"desc")->whereIn('cate_id',$c_id)->get();
        }else if($field == 'is_new'){
            $goodsInfo = DB::table('shop_goods')->where("is_new",1)->get();
        }else{
            $goodsInfo = DB::table('shop_goods')->orderBy($field,"desc")->get();
        }
        // 商品路径
        $path = config('app.path');

        return view('goods.div',['goodsInfo' => $goodsInfo,'path'=>$path]);
    }

    // 商品详情
    public function goodsDetail($id){
        if(!$id){
            return;
        }

        // 商品路径
        $path = config('app.path');
        // 商品信息
        $info = DB::table('shop_goods')->where(['goods_id'=>$id])->first();
        $info->goods_imgs = explode('|',rtrim($info->goods_imgs,'|'));
//        dd($info);
        $info->goods_desc = rtrim(ltrim($info->goods_desc,"<p>"),'</p>');

        // 检测是否收藏
        $collect = DB::table('shop_collect')->where(['goods_id'=>$id,'user_id'=>Session::get('id')])->count();
//        dd($collect);
//        dd($goods_info);
        return view('goods.goodsDetail',compact('info','path','collect'));
    }

    // 收藏
    public function collect(){
        $goods_id = request()->goods_id;
        $user_id = Session::get('id');
        if(!$goods_id){
            echo json_encode(['font'=>'请重新操作','code'=>5]);exit;
        }
        if(!$user_id){
            echo json_encode(['font'=>'请重新操作','code'=>5]);exit;
        }
        $data = [
            'goods_id' =>$goods_id,
            'user_id' =>$user_id,
        ];
        $res = DB::table('shop_collect')->insert($data);
        if($res){
            echo json_encode(['font'=>'收藏成功','code'=>6]);
        }else{
            echo json_encode(['font'=>'收藏失败','code'=>5]);
        }
    }

}
