<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    // 登录
    public function login(){
        $token = request()->_token;
        if(!$token&&request()->isMethod('post')){
            $data = request()->only(['account','pwd']);
            // 验证
            if(empty($data['account'])){
                $this->message('账号不能为空',5);exit;
            }
            if(empty($data['pwd'])){
                $this->message('密码不能为空',5);exit;
            }

            // 条件
            $arr = DB::table('shop_user')
                ->where('user_email',$data['account'])
                ->orWhere('user_tel' ,$data['account'])
                ->first();

            // 错误次数
            $error_num = $arr->error_num;

            // 最后一次错误时间
            $last_error_time = $arr->last_error_time;

            // 当前时间
            $now = time();

            // 条件
            $updateWhere = [
                'user_id' => $arr->user_id
            ];

            if($arr){
                if(md5($data['pwd']) == $arr->user_pwd){
                    // 判断账号是否在锁定中
                    if($error_num >= 3&&$now-$last_error_time>3600){
                        $lastTime = 60-(ceil(($now-$last_error_time)/60));
                        $this->message('账号锁定中，请您于'.$lastTime.'分钟后登录！');
                    }else{
                        // 密码正确  错误次数清零 错误时间为空
                        $updateValue = [
                            'error_num' => 0,
                            'last_error_time' => null
                        ];
                        $res =DB::table('shop_user')-> where($updateWhere)->update($updateValue);

                        // 存sessio
                        Session::put(['user_name' => $data['account'],'id' => $arr->user_id]);

                        // 提示登录成功
                        $this->message('登录成功',6);
                    }
                }else{
                    // 密码错误
                    if($now-$last_error_time>3600){
                        // 锁定时间过后密码出错 错误次数为1 错误时间为当前时间 并提示可操作次数
                        $updateValue = [
                            'error_num' => 1,
                            'last_error_time' => $now
                        ];
                        $res = DB::table('shop_user')-> where($updateWhere)->update($updateValue);
                        // dump($res);
                        $this->message('账号或密码错误，你还有2次机会登录');exit;
                    }else{
                        // 密码错误3次时，提示账号锁定与多长时间后可再登录
                        if($error_num >= 3){
                            $lastTime = 60-(ceil(($now-$last_error_time)/60));
                            $this->message('账号已锁定，请您于'.$lastTime.'分钟后登录！');exit;
                        }else{
                            // 密码错误，次数+1 ，最后一次错误时间入库
                            $updateValue = [
                                'error_num' => $error_num+1,
                                'last_error_time' => $now
                            ];
                            $res = DB::table('shop_user')-> where($updateWhere)->update($updateValue);
                            // dump($res);
                            // 剩余可操作次数
                            $num = 3-($error_num+1);
                            if($num > 0){
                                $this->message('账号或密码错误，你还有'.$num.'次机会登录');exit;
                            }else{
                                $this-> message('账号已锁定，请您于一小时后登录！');exit;
                            }
                        }
                    }
                }
            }else{
                $this->message('账号错误',5);exit;
            }
        }else{
            return view('login/login');
        }
    }

    // 注册
    public function register(){

        return view('login/register');
    }

    // 消息提示
    public function message($font,$num=3){
        echo json_encode(['font'=>$font,'code'=>$num]);
    }

    // 退出
    public function quit(){
        // 删除
        Session::forget('user_name');
        return redirect('/');
    }
}
