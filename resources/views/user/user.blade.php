﻿@include('layouts.layout')
<body>
<div class="maincont">
    <div class="userName">
        <dl class="names">
            <dt><img src="/shop/images/user01.png" /></dt>
            <dd>
                <h3>天池不动峰</h3>
            </dd>
            <div class="clearfix"></div>
        </dl>
        <div class="shouyi">
            <dl>
                <dt>我的余额</dt>
                <dd>0.00元</dd>
            </dl>
            <dl>
                <dt>我的积分</dt>
                <dd>0</dd>
            </dl>
            <div class="clearfix"></div>
        </div><!--shouyi/-->
    </div><!--userName/-->

    <ul class="userNav">
        <li><span class="glyphicon glyphicon-list-alt"></span><a href="{{url('user/order')}}">我的订单</a></li>
        <div class="height2"></div>
        <div class="state">
            <dl>
                <dt><a href="{{url('user/order')}}"><img src="/shop/images/user1.png" /></a></dt>
                <dd><a href="{{url('user/order')}}">待支付</a></dd>
            </dl>
            <dl>
                <dt><a href="{{url('user/order')}}"><img src="/shop/images/user2.png" /></a></dt>
                <dd><a href="{{url('user/order')}}">代发货</a></dd>
            </dl>
            <dl>
                <dt><a href="{{url('user/order')}}"><img src="/shop/images/user3.png" /></a></dt>
                <dd><a href="{{url('user/order')}}">待收货</a></dd>
            </dl>
            <dl>
                <dt><a href="{{url('user/order')}}"><img src="/shop/images/user4.png" /></a></dt>
                <dd><a href="{{url('user/order')}}">全部订单</a></dd>
            </dl>
            <div class="clearfix"></div>
        </div><!--state/-->
        <li><span class="glyphicon glyphicon-usd"></span><a href="{{url('user/quan')}}">我的优惠券</a></li>
        <li><span class="glyphicon glyphicon-map-marker"></span><a href="{{url('user/address')}}">收货地址管理</a></li>
        <li><span class="glyphicon glyphicon-star-empty"></span><a href="{{url('user/collect')}}">我的收藏</a></li>
        <li><span class="glyphicon glyphicon-usd"></span><a href="{{url('user/withdraw')}}">余额提现</a></li>
    </ul><!--userNav/-->

    <div class="lrSub">
        <a href="/login/quit">退出登录</a>
    </div>

    @include('layouts.footer')
</div><!--maincont-->

<script>
    $('.spinnerExample').spinner({});
</script>
</body>
