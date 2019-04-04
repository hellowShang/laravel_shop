<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use think\Session;


class UserController extends Controller
{
    // 消息提示
    public function message($font,$num=3){
        echo json_encode(['font'=>$font,'code'=>$num]);
    }

    // 用户
    public function user(){
        return view('user.user');
    }

    // 用户订单详情
    public function order(){
            $user_id = session('id');
            $where = [
                'o.user_id'=> $user_id,
                'o.pay_status' => 1,
                'd.status' => 1,
                'o.status' => 1,
            ];
//        dd($where);
            $orderInfo = DB::table('shop_order as o')
                ->join('shop_order_detail as d','o.order_id','=','d.order_id')
                ->where($where)
                ->get();
//        dd($orderInfo);
            $path = config('app.path');
//        dd($path);
            return view('user.order',['orderInfo'=>$orderInfo,'path'=>$path]);
    }

    // 重新获取用户订单详情
    public function unset(){
        $status = request()->status;
//        var_dump($status);
        $user_id = session('id');
        $where = [
            'o.user_id'=> $user_id,
        ];
        if(empty($status)||$status == 1){
            $where['o.pay_status']=1;
            $where['o.status']=1;
            $where['d.status']=1;
        }else if($status == 2){
            $where['o.pay_status']=2;
            $where['o.status']=1;
            $where['d.status']=1;
        }else if($status == 3){
            $where['o.status']=2;
            $where['d.status']=2;
        }else{
            $where['o.pay_status']=2;
            $where['o.status']=2;
            $where['d.status']=2;
        }
//        var_dump($where);
        $orderInfo = DB::table('shop_order as o')
            ->join('shop_order_detail as d','o.order_id','=','d.order_id')
            ->where($where)
            ->get();
//        var_dump($orderInfo);die;
        $path = config('app.path');
        return view('user.div',['orderInfo'=>$orderInfo,'path'=>$path]);
    }

    // 优惠券
    public function quan(){
        return view('user.quan');
    }

    // 收货地址数据获取
    public function address(){
        $where = [
            'user_id' => session('id'),
            'address_status' => 1
        ];
        $addressInfo = $this->getAddressInfo($where);
//        dd($addressInfo);

        return view('user.address',['addressInfo' =>$addressInfo]);
    }

    // 收货地址添加
    public function addressAdd(){
        if(request()->ajax() == true){
            $data = request()->all();
            // 验证
            $this->validates($data);

            // 入库
            $data['user_id'] = session('id');
            $data['create_time'] = time();
            if($data['is_default'] == 1){
                DB::beginTransaction();
                $res = DB::table('shop_address')->where(['user_id'=>session('id'),'address_status'=>1])->update(['is_default'=>2]);
                $res1 = DB::table('shop_address')->insert($data);
                if ($res&&$res1){
                    //如果语句执行成功就进行提交
                    DB::commit();
                    $this->message('保存成功',6);
                }else{
                    //如果失败就回滚
                    DB::rollback();
                    $this->message('保存失败',5);
                }
            }else{
                $res = DB::table('shop_address')->insert($data);
                if($res){
                    $this->message('保存成功',6);
                }else{
                    $this->message('保存失败',5);
                }
            }
        }else{
            // 三级联动省查询
            $province = $this->province(0);
            return view('user.addressadd',compact('province'));
        }
    }

    // 三级联动 省数据查询
    public function province($pid){
        $provinceInfo = DB::table("shop_area")-> where(['pid' =>$pid])->get();
        if(empty($provinceInfo)){
            return false;
        }else{
            return $provinceInfo;
        }
    }

    // 市、县数据获取
    public function getArea(){
        $id = request()->id;
        if(empty($id)){
            exit;
        }
        $areaInfo =DB::table('shop_area')->where(['pid' =>$id])->get();
        // echo model('Area')->getLastSql();die;
//         print_r($areaInfo);die;
        if(count($areaInfo)){
            echo json_encode(['areaInfo' => $areaInfo,'code' => 6]);
        }else{
            exit;
        }
    }

    // 收货地址修改
    public function addressUpd(){
//        dd($id);
        if(request()->ajax() == true){
            $data = request()->all();
//            var_dump($data);
            // 验证
            $this->validates($data);

            // 入库
            // 判断是否默认
            if($data['is_default'] == 1){
                DB::beginTransaction();
                $res = DB::table('shop_address')->where(['user_id'=>session('id'),'address_status'=>1])->update(['is_default'=>2]);
                $res1 = DB::table('shop_address')->where(['user_id'=>session('id'),'address_id'=>$data['address_id']])->update($data);
                if ($res !== false&&$res1 !== false){
                    //如果语句执行成功就进行提交
                    DB::commit();
                    $this->message('修改成功',6);
                }else{
                    //如果失败就回滚
                    DB::rollback();
                    $this->message('修改失败',5);
                }
            }else{
                $res = DB::table('shop_address')->where(['user_id'=>session('id'),'address_status'=>1,'address_id'=>$data['address_id']])->update($data);
                if($res !== false){
                    $this->message('修改成功',6);
                }else{
                    $this->message('修改失败',5);
                }
            }
        }else{
            $id = request()->id;
            if($id){
                $updateInfo = DB::table('shop_address')->where(['user_id'=>session('id'),'address_status'=>1,'address_id'=>$id])->first();
                // 三级联动省、市、县查询
                $province = $this->province(0);
                $city = $this->province($updateInfo->province);
                $area = $this->province($updateInfo->city);
                return view('user.addressupd',compact('updateInfo','province','city','area'));
            }else{
                $this->message('额，出错了',5);exit;
            }
        }
    }

    // 收货地址数据展示
    public function getAddressInfo($where){

        $addressInfo = DB::table('shop_address') -> where($where)->get();
        // print_r($areaInfo);die;
        if(!empty($addressInfo)){
            $addressInfo = json_decode(json_encode($addressInfo),true);
            foreach($addressInfo as $k=> $v){
                $addressInfo[$k]['province'] = DB::table('shop_area')-> select('name')->where(['id'=>$v['province']])->first();
                $addressInfo[$k]['city'] = DB::table('shop_area')-> select('name')->where(['id'=>$v['city']])-> first();
                $addressInfo[$k]['area'] = DB::table('shop_area')-> select('name')->where(['id'=>$v['area']])-> first();
            }
           return $addressInfo;
        }else{
            return false;
        }
    }

    // 验证
    public function validates($data){
        if($data['address_id'] ?? ''){
            $arr = DB::table('shop_address')->where([['address_id','!=',$data['address_id']],['address_name',$data['address_name']]])->count();
        }else{
            $arr = DB::table('shop_address')->where('address_name',$data['address_name'])->count();
        }

        $reg = "/^[A-Za-z_\x{4e00}-\x{9fa5}]{2,16}$/u";
        $reg1 = "/^\d{11}$/";
        if(empty($data['address_name'])){
            $this->message('收货人必填',5);exit;
        }else if($arr == 1){
            $this->message('收货人已存在',5);exit;
        }else if(!preg_match($reg,$data['address_name'])){
            $this->message('收货人字母、汉字组成非数字2-16位',5);exit;
        }
        if(empty($data['province'])){
            $this->message('省必选',5);exit;
        }
        if(empty($data['city'])){
            $this->message('市必选',5);exit;
        }
        if(empty($data['area'])){
            $this->message('区必选',5);exit;
        }
        if(empty($data['address_add'])){
            $this->message('详细地址必填',5);exit;
        }
        if(empty($data['address_tel'])){
            $this->message('手机号必填',5);exit;
        }else if(!preg_match($reg1,$data['address_tel'])){
            $this->message('手机号数字11位',5);exit;
        }
    }

    // 收藏
    public function collect(){
        $user_id = session('id');
        $goods_id = DB::table('shop_collect')->select('goods_id')->where('user_id',$user_id)->get();
        $goods_id = json_decode(json_encode($goods_id),true);
        $goods_id = array_column($goods_id,'goods_id');
        $collect = DB::table('shop_goods')->whereIn('goods_id',$goods_id)->get();
        $count = DB::table('shop_goods')->whereIn('goods_id',$goods_id)->count();
//        dd($collect);
        $path = config('app.path');
        return view('user.collect',compact('collect','count','path'));
    }

    // 收藏删除、全部删除
    public function collectDel(){
        $id = request()->goods_id;
        $user_id = session('id');
        if(!$id){
            $res = DB::table('shop_collect')->where('user_id',$user_id)->delete();
        }else{
            $res = DB::table('shop_collect')->where(['user_id'=>$user_id,'goods_id'=>$id])->delete();
        }
        if($res){
            $this->message('取消成功',6);
        }else{
            $this->message('取消失败',5);
        }
    }

    // 提现
    public function withdraw(){
        return view('user.withdraw');
    }
}
