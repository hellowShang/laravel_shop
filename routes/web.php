<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// 首页
Route::any('/','IndexController@index');

// 登录
Route::any('/login/login','LoginController@login');
// 注册
Route::any('/login/register','LoginController@register');
// 退出
Route::any('/login/quit','LoginController@quit')->middleware('login');

// 用户
Route::any('/user/user','UserController@user')->middleware('login');
// 用户订单详情
Route::any('/user/order','UserController@order')->middleware('login');
// 优惠券
Route::any('/user/quan','UserController@quan')->middleware('login');
// 收货地址管理
Route::any('/user/address','UserController@address')->middleware('login');
// 收货地址添加
Route::any('/user/addressadd','UserController@addressAdd')->middleware('login');
// 市、县获取
Route::any('/user/getArea','UserController@getArea')->middleware('login');
// 收货地址修改
Route::any('/user/addressupd/{id?}','UserController@addressUpd')->middleware('login');
// 收藏
Route::any('/user/collect','UserController@collect')->middleware('login');
// 提现
Route::get('/user/withdraw','UserController@withdraw')->middleware('login');


// 商品展示
Route::any('/goods/goodsList/{id?}','GoodsController@goodsList')->middleware('login');
// 重新获取商品信息
Route::any('/goods/getGoodsInfo','GoodsController@getGoodsInfo')->middleware('login');
// 收藏
Route::any('/goods/collect','GoodsController@collect')->middleware('login');
// 收藏删除
Route::any('/user/collectDel','UserController@collectDel')->middleware('login');
// 收藏筛选
Route::any('/user/unset','UserController@unset')->middleware('login');
// 商品详情
Route::any('/goods/goodsDetail/{id}','GoodsController@goodsDetail')->middleware('login');


// 加入购物车
Route::any('/car/cartAdd','CarController@cartAdd')->middleware('login');
// 购物车
Route::any('/car/car','CarController@car')->middleware('login');
// 清空购物车
Route::any('/car/clearCar','CarController@clearCar')->middleware('login');
// 总价获取
Route::any('/car/getCountPrice/{id?}','CarController@getCountPrice')->middleware('login');
// 结算
Route::any('/car/pay/{id}','CarController@pay')->middleware('login');
// 订单提交
Route::any('/car/submitPay','CarController@submitPay')->middleware('login');
// 订单展示
Route::any('/car/success','CarController@success')->middleware('login');
// 支付
Route::any('/car/alipay/{order_no}','CarController@alipay')->middleware('login');
// 支付成功(同步)
Route::any('/car/returnpay','CarController@returnpay')->middleware('login');
// 支付成功(异步)
Route::any('/car/notifypay','CarController@notifypay');
// 支付成功(异步提示)
Route::any('/car/paymessage','CarController@paymessage')->middleware('login');


// 加入分销
Route::any('/melt/joinmelt','MeltController@joinmelt');
// 分销
Route::any('/melt/melt','MeltController@melt')->middleware('login');
