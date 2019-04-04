<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Session;
use Log;
class CarController extends Controller
{
    // 加入购物车
    public function cartAdd(){
        $buy_number = request()->buy_number;
        $goods_id = request()->goods_id;
        $goods_num = request()->goods_num;

        // 验证
        if(empty($goods_id)){
            $this->message('请重新操作！', 5);exit;
        }
        if(empty($buy_number)){
            $this->message('请重新操作！', 5);exit;
        }else if($buy_number>$goods_num){
            $this->message("最多购买".$goods_num."件");exit;
        }else if(is_int($buy_number)){
            $this->message('请正确操作！',5);exit;
        }
        $user_id = Session::get('id');

        // 检测数据库是否有该用户的该商品记录
        $where = [
            'goods_id' => $goods_id,
            'user_id' => $user_id,
            'cart_status' => 1
        ];
        $cartInfo = DB::table('shop_cart')->where($where)->first();
        if($cartInfo){
            $old_buy_number = $cartInfo->buy_number;
            $new_buy_number = $old_buy_number + $buy_number;
            $data = [
                'buy_number' => $new_buy_number,
                'update_time' => time()
            ];
            $res = DB::table('shop_cart')->where($where)->update($data);
        }else{
            $data = [
                'buy_number'=> $buy_number,
                'goods_id'=> $goods_id,
                'create_time' => time(),
                'update_time' => time(),
                'user_id' => $user_id
            ];
            $res = DB::table('shop_cart')->insert($data);
        }

        if($res){
            $this->message('加入购物车成功',6);
        }else{
            $this->message('失败了，重新来吧',5);
        }
    }


    // 消息提示
    public function message($font,$num=3){
        echo json_encode(['font'=>$font,'code'=>$num]);
    }

    // 购物车数据展示
    public function car(){
        // 商品图片路径
        $path = config('app.path');

        $user_id = Session::get('id');
        $goods_id = DB::table('shop_cart')
            ->select('goods_id')
            ->where(['user_id'=> $user_id,'cart_status'=>1])
            ->orderBy('create_time','desc')
            ->get()
            ->map(function ($value) {return (array)$value;})
            ->toArray();
        // 数据库获取购物车数据
        $cartInfo = $this-> getCartInfo($goods_id,$user_id);
        if(!$cartInfo){
            $count = 0;
        }else{
            $count = count($cartInfo);
        }
        return view('car.car',compact('cartInfo','count','path'));
    }

    // 数据库获取购物车数据
    public function getCartInfo($goods_id,$user_id){
//        dd($goods_id);
        if($goods_id){
            if(is_array($goods_id)){
                $cartInfo = DB::table('shop_cart as c')
                    ->join('shop_goods as g','g.goods_id','=','c.goods_id')
                    ->orderBy('c.update_time','desc')
                    ->where(['cart_status'=>1,'user_id'=>$user_id])
                    ->whereIn('c.goods_id',$goods_id)
                    ->get();
            }else{
                $cartInfo = DB::table('shop_cart as c')
                    ->join('shop_goods as g','g.goods_id','=','c.goods_id')
                    ->orderBy('c.update_time','desc')
                    ->where(['cart_status'=>1,'user_id'=>$user_id,'c.goods_id'=>$goods_id])
                    ->get();
            }
        }else{
            $cartInfo =null;
        }
        return $cartInfo;
    }

    // 获取总价
    public function getCountPrice(){
        $goods_id = request()->goods_id;
//        var_dump($goods_id);die;
        if($goods_id){
            echo $this-> price($goods_id);
        }else{
            echo 0;
        }
    }

    // 总价计算
    public function price($goods_id){
        $goods_id = explode(',',rtrim($goods_id,','));
        $user_id = Session::get('id');

        // 获取总价
        $cartInfo = DB::table('shop_cart as c')
            ->select('buy_number','self_price')
            ->join('shop_goods as g','g.goods_id','=','c.goods_id')
            ->where(['cart_status'=>1,'user_id'=>$user_id])
            ->whereIn('c.goods_id',$goods_id)
            ->get();
        $count = 0;
        foreach($cartInfo as $k=>$v){
            $count+= $v->buy_number * $v->self_price;
        }
//        var_dump($count);die;
        return $count;
    }

    // 删除数据、清空购物车、删除地址
    public function clearCar(){
        $goods_id = request()->goods_id;
        $type = request()->type;
        if(!$goods_id){
            $this->message('请重新操作',5);exit;
        }
//        var_dump($goods_id);
//        var_dump(strpos($goods_id,','));die;
        if(strpos($goods_id,',') != false){
            $goods_id = explode(',',rtrim($goods_id,','));
        }

        if($type == 3){
            if(strpos($goods_id,',') != false){
                $res = DB::table('shop_address')->where('user_id',Session::get('id'))->whereIn('address_id',$goods_id)->update(['address_status' => 2]);
            }else{
                $res = DB::table('shop_address')->where(['user_id'=>Session::get('id'),'address_id'=>$goods_id])->update(['address_status' => 2]);
            }
        }else if($type == 2){
            $res = DB::table('shop_cart')->where('user_id',Session::get('id'))->whereIn('goods_id',$goods_id)->update(['cart_status' => 2]);
        }else{
            $res = DB::table('shop_cart')->where(['user_id'=>Session::get('id'),'goods_id'=>$goods_id])->update(['cart_status' => 2]);
        }
        if($res){
            $this->message('成功消除了忧愁',6);
        }else{
            $this->message('忧愁消除失败了呢',5);
        }
    }

    // 结算
    public function pay($id){
        $user_id = Session::get('id');
        if(!$id){
            return;
        }
        // 计算总价
        $count = $this->price($id);

        // 数据库获取购物车数据
        if(strpos($id,',')){
            $goods_id = explode(',',$id);
        }else{
            $goods_id = $id;
        }
//        dd($goods_id);
        $cartInfo = $this-> getCartInfo($goods_id,$user_id);

        // 获取收货地址
        $where = [
            'user_id' =>$user_id,
            'is_default' => 1,
            'address_status' =>1
        ];
        $addressInfo = $this->getAddressInfo($where);
        if($addressInfo == false){
            $where = [
                'user_id' =>$user_id,
                'address_status' =>1
            ];
            $info = $this->getAddressInfo($where);
            $addressInfo = [];
            foreach($info as $k=>$v){
                if($k == 0){
                    $addressInfo[] = $v;
                }
            }
        }
//        var_dump($addressInfo);die;
        // 商品图片路径
        $path = config('app.path');
//        dd($cartInfo);
        return view('car.pay',compact('count','cartInfo','path','addressInfo'));
    }

    // 收货地址数据展示
    public function getAddressInfo($where){

        $addressInfo = DB::table('shop_address')->orderBy('create_time','desc') -> where($where)->get();
//         print_r($addressInfo);die;
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

    // 订单提交
    public  function submitPay(){
        $data = request()->all();
        if($data['address_time'] ?? ''){
            $data['address_time'] = str_replace('T',' ',$data['address_time']);
        }
        // 开启事务
        DB::beginTransaction();

        // 捕获异常
        try{
            // 订单信息数据入库
            // 订单号获取
            $user_id = session('id');
            $order_no = $user_id.rand(1000,9999).time();
            // 计算总价
            $order_amount = $this->price($data['goods_id']);
            $orderInfo['user_id'] = $user_id;
            $orderInfo['order_no'] = $order_no;
            $orderInfo['order_amount'] = $order_amount;
            $orderInfo['pay_way'] = $data['pay_type'];
            $orderInfo['order_text'] = $data['address_time'];
            $orderInfo['create_time'] = time();
            $orderInfo['update_time'] = time();
            $res1 = DB::table('shop_order')->insertGetId($orderInfo);
//            var_dump($res1);
            if($res1 == 0){
                throw new \Exception('订单信息入库失败');
            }

            $order_id = $res1;
            // 订单商品数据入详情
            if(strpos($data['goods_id'],',')){
                $goods_id = explode(',',$data['goods_id']);
                $goodsInfo = DB::table('shop_cart as c')
                    ->select('c.buy_number','g.self_price','g.goods_img','g.goods_name','c.goods_id')
                    ->join('shop_goods as g','g.goods_id','=','c.goods_id')
                    ->where(['cart_status'=>1,'user_id'=>$user_id])
                    ->whereIn('c.goods_id',$goods_id)
                    ->get();
            }else{
                $goods_id = $data['goods_id'];
                $goodsInfo = DB::table('shop_cart as c')
                    ->select('c.buy_number','g.self_price','g.goods_img','g.goods_name','c.goods_id')
                    ->join('shop_goods as g','g.goods_id','=','c.goods_id')
                    ->where(['cart_status'=>1,'user_id'=>$user_id,'c.goods_id'=>$goods_id])
                    ->get();
            }
            $goodsInfo = json_decode(json_encode($goodsInfo),true);
            foreach($goodsInfo as $k=>$v){
                $goodsInfo[$k]['user_id'] = $user_id;
                $goodsInfo[$k]['order_id'] = $order_id;
                $goodsInfo[$k]['create_time'] = time();
                $goodsInfo[$k]['update_time'] = time();
            }
            $res2 = DB::table('shop_order_detail')->insert($goodsInfo);
            if(!$res2){
                throw new \Exception('订单商品信息入库失败');
            }

            // 订单收货地址数据入库
            $addressInfo = DB::table('shop_address')->where('address_id',$data['address_id'])->first();
            $addressInfo = json_decode(json_encode($addressInfo),true);
            unset($addressInfo['address_id']);
            unset($addressInfo['address_status']);
            unset($addressInfo['is_default']);
            $addressInfo['order_id'] = $order_id;
            $addressInfo['user_id'] = $user_id;
            $addressInfo['update_time'] = time();
            $res3 = DB::table('shop_order_address')->insert($addressInfo);
            if(!$res3){
                throw new \Exception('订单收货地址信息入库失败');
            }

            //购物车当前数据删除
            if(is_array($goods_id)){
                $res4 = DB::table('shop_cart')->where('user_id',$user_id)->whereIn('goods_id',$goods_id)->update(['cart_status'=>2]);
            }else{
                $where = [
                    'goods_id' =>$goods_id,
                    'user_id' => $user_id
                ];
                $res4 = DB::table('shop_cart')->where($where)->update(['cart_status'=>2]);
            }

//            $res4 = false;
            if(!$res4){
                throw new \Exception('购物车商品信息删除失败');
            }

            //商品的库存同时减少

            //如果语句执行成功就进行提交
            DB::commit();
            session(['order_id'=>$order_id]);

            $this->message('下单成功',6);
        }catch (\Exception $e){
            //如果失败就回滚
            DB::rollback();
            $this->message($e->getMessage(),5);
        }
    }

    // 订单展示
    public function success(){
        $order_id = session('order_id');
        $orderInfo = DB::table('shop_order')->where('order_id',$order_id)->first();
        return view('car.success',['orderInfo' => $orderInfo]);
    }

    //  支付
    public function alipay($order_no){
//        $order_no = '';
        if(!$order_no){
            return redirect('/car/success')->with('status','无效的订单');
        }
        // 根据订单号查询是否有此订单
        $orderInfo = DB::table('shop_order')->select('order_amount','order_text')->where('order_no',$order_no)->first();
//        var_dump($orderInfo);die;
        if($orderInfo->order_amount < 0){
            return redirect('/car/success')->with('status','无效的订单');
        }else{
//            echo app_path('alipay/pagepay/service/AlipayTradeService.php');die;
            require_once app_path('alipay/wappay/service/AlipayTradeService.php');
            require_once app_path('alipay/wappay/buildermodel/AlipayTradeWapPayContentBuilder.php');

            //商户订单号，商户网站订单系统中唯一订单号，必填
            $out_trade_no = trim($order_no);

            //订单名称，必填
            $subject = '商品支付';

            //付款金额，必填
            $total_amount = $orderInfo->order_amount;

            //商品描述，可空
            $body = $orderInfo->order_text;

            //超时时间
            $timeout_express="1m";

            //构造参数
            $payRequestBuilder = new \AlipayTradeWapPayContentBuilder();
            $payRequestBuilder->setBody($body);
            $payRequestBuilder->setSubject($subject);
            $payRequestBuilder->setTotalAmount($total_amount);
            $payRequestBuilder->setOutTradeNo($out_trade_no);
            $payRequestBuilder->setTimeExpress($timeout_express);

            $payResponse = new \AlipayTradeService(config('alipay'));


            $result=$payResponse->wapPay($payRequestBuilder,config('alipay.return_url'),config('alipay.notify_url'));

            return;
        }
    }

    // 支付成功同步返回信息
    public  function returnpay(){

//        dump($_GET);die;
        //商户订单号
        $out_trade_no = htmlspecialchars($_GET['out_trade_no']);

        // 商品总价
        $total_amount = htmlspecialchars($_GET['total_amount']);

        //支付宝交易号
        $trade_no = htmlspecialchars($_GET['trade_no']);
        //根据价格和订单号查询是否有此订单
        $data = DB::table('shop_order')->where(['order_no'=>$out_trade_no,'order_amount'=>$total_amount])->first();
//        dd($data);
        if($data){
            return redirect('/car/paymessage')->with('status',"支付成功,交易账号为$trade_no");
        }else{
            return redirect('/car/paymessage')->with('status',"支付失败");
        }
    }

    // 支付成功异步修改数据
    public function notifypay(){
        require_once app_path('alipay/wappay/service/AlipayTradeService.php');

        // 接收订单商品信息并验证
        $arr=$_REQUEST;
        $str = var_export($arr,true);
        Log::channel('pay')->info($str);
        $alipaySevice = new \AlipayTradeService(config('alipay'));
        $result = $alipaySevice->check($arr);
        if($result) {//验证成功
            //商户订单号
            $out_trade_no = $_POST['out_trade_no'];

            // 商品总价
            $total_amount = $_POST['total_amount'];

            if($_POST['trade_status'] == 'TRADE_FINISHED') {

            }else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
                $orderInfo = DB::table('shop_order')->where(['order_no'=>$out_trade_no,'order_amount'=>$total_amount])->first();
                if($orderInfo){
                    // 修改支付状态
                    DB::table('shop_order')->where('order_no',$out_trade_no)->update(['pay_status'=>2]);

                    // 减少库存
                    $user_id = DB::table('shop_order')->where(['order_no'=>$out_trade_no,'order_amount'=>$total_amount])->value('user_id');
                    $order_id = DB::table('shop_order')->where(['order_no'=>$out_trade_no,'order_amount'=>$total_amount])->value('order_id');
                    $buy_number = DB::table('shop_order_detail')->select('buy_number','goods_id')
                        ->where(['order_id'=>$order_id,'user_id'=>$user_id])->get();
                    $goodsInfo = DB::table('shop_goods')->get();
                    $buy_number = json_decode(json_encode($buy_number),true);
                    $goodsInfo = json_decode(json_encode($goodsInfo),true);
                    foreach ($buy_number as $key=>$val){
                        if(in_array($val['goods_id'],array_column($goodsInfo,'goods_id'))){
                            foreach($goodsInfo as $k=>$v){
                                if($val['goods_id'] == $v['goods_id']){
                                    $res5 = DB::table('shop_goods')->where('goods_id',$val['goods_id'])
                                        ->update(['goods_num'=>$v['goods_num']-$val['buy_number']]);
                                }
                            }
                        }
                    }
                }
            }
            echo "success";	//请不要修改或删除
        }else {
            //验证失败
            echo "fail";
        }
    }

    // 支付成功提示
    public function paymessage(){
        return view('car.paymessage');
    }
}
