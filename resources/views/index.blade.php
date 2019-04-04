@include('layouts.layout')
<body>
{{--@if (session('status'))--}}
    {{--<div class="alert alert-success">--}}
        {{--{{ session('status') }}--}}
    {{--</div>--}}
{{--@endif--}}
<div class="maincont">
    <div class="head-top">
        <img src="/shop/images/head.jpg" />
        <dl>
            <dt><a href="{{url('user/user')}}"><img src="/shop/images/touxiang.jpg" /></a></dt>
            <dd>
                <h1 class="username"></h1>
                <ul>
                    <li><a href="{{url('goods/goodsList')}}"><strong>34</strong><p>全部商品</p></a></li>
                    <li><a href="javascript:;"><span class="glyphicon glyphicon-star-empty"></span><p>收藏本店</p></a></li>
                    <li style="background:none;"><a href="javascript:;"><span class="glyphicon glyphicon-picture"></span>
                            <div class="site-demo-button" id="layerDemo" style="margin-bottom: 0;">
                                <p data-method="notice" class="layui-btn">二维码</p>
                            </div>
                        </a></li>

                    <div class="clearfix"></div>
                </ul>
            </dd>
            <div class="clearfix"></div>
        </dl>
    </div><!--head-top/-->
    <form action="#" method="get" class="search">
        <input type="text" class="seaText fl" />
        <input type="submit" value="搜索" class="seaSub fr" />
    </form><!--search/-->
    <ul class="reg-login-click">
        @if($session)
            <li style="margin-left:100px;">欢迎<font color="red">{{$session}}</font>登录</li>
        @else
            <li><a href="{{url('login/login')}}">登录</a></li>
            <li><a href="{{url('login/register')}}" class="rlbg">注册</a></li>
            <div class="clearfix"></div>
        @endif
    </ul><!--reg-login-click/-->
    <div id="sliderA" class="slider">
        <img src="/shop/images/c1.gif" />
        <img src="/shop/images/c2.gif" />
        <img src="/shop/images/c3.gif" />
        <img src="/shop/images/c4.gif" />
        <img src="/shop/images/c5.gif" />
    </div><!--sliderA/-->
    <ul class="pronav">
        @foreach($cateInfo as $key=>$val)
            @if($key+1 % 2 == 0)
                <li><a href="{{url('goods/goodsList')}}/{{$val->cate_id}}">{{$val->cate_name}}</a></li>
            @else
                <li><a href="{{url('goods/goodsList')}}/{{$val->cate_id}}">{{$val->cate_name}}</a></li>
            @endif
        @endforeach
        <div class="clearfix"></div>
    </ul><!--pronav/-->
    <div class="index-pro1">
        @foreach($goodsInfo2 as $key=>$val)
            @if($key+1 % 2==0)
                <div class="index-pro1-list">
                    <dl >
                        <dt><a href="{{url('goods/goodsDetail')}}/{{$val->goods_id}}"><img src="/uploads/goodsimgs/{{$val->goods_img}}" style="width:80px;height:80px;" /></a></dt>
                        <dd class="ip-text"><a href="{{url('goods/goodsDetail')}}/{{$val->goods_id}}">{{$val->goods_name}}</a><span>已售：{{$val->goods_num}}</span></dd>
                        <dd class="ip-price"><strong>¥{{$val->self_price}}</strong> <span>¥{{$val->market_price}}</span></dd>
                    </dl>
                </div>
            @else
                <div class="index-pro1-list">
                    <dl >
                        <dt><a href="{{url('goods/goodsDetail')}}/{{$val->goods_id}}"><img src="/uploads/goodsimgs/{{$val->goods_img}}" style="width:80px;height:80px;" /></a></dt>
                        <dd class="ip-text"><a href="{{url('goods/goodsDetail')}}/{{$val->goods_id}}">{{$val->goods_name}}</a><span>已售：{{$val->goods_num}}</span></dd>
                        <dd class="ip-price"><strong>¥{{$val->self_price}}</strong> <span>¥{{$val->market_price}}</span></dd>
                    </dl>
                </div>
            @endif
            @endforeach
        <div class="clearfix"></div>
    </div><!--index-pro1/-->
    <div class="prolist">
        @foreach($goodsInfo1 as $key=>$val)
        <dl>
            <dt><a href="{{url('goods/goodsDetail')}}/{{$val->goods_id}}"><img src="/uploads/goodsimgs/{{$val->goods_img}}" width="80" height="80" /></a></dt>
            <dd>
                <h3><a href="{{url('goods/goodsDetail')}}/{{$val->goods_id}}">{{$val->goods_name}}</a></h3>
                <div class="prolist-price"><strong>¥{{$val->self_price}}</strong> <span>¥{{$val->market_price}}</span></div>
                <div class="prolist-yishou"><span>5.0折</span> <em>已售：{{$val->goods_num}}</em></div>
            </dd>
            <div class="clearfix"></div>
        </dl>
        @endforeach
    </div><!--prolist/-->
    <div class="joins"><a href="{{url('melt/joinmelt')}}"><img src="/shop/images/jrwm.jpg" /></a></div>
    <div class="copyright">Copyright &copy; <span class="blue">这是就是三级分销底部信息</span></div>

    @include('layouts.footer')
</div><!--maincont-->

<script>
    $(function () {
        $("#sliderA").excoloSlider();
        layui.use('layer',function(){
            //触发事件
            var active = {
                notice: function(){
                    //示范一个公告层
                    layer.open({
                        type: 1
                        ,title: false //不显示标题栏
                        ,closeBtn: false
                        ,area: '300px;'
                        ,shade: 0.8
                        ,id: 'LAY_layuipro' //设定一个id，防止重复弹出
                        ,btn: [ '关闭']
                        ,btnAlign: 'c'
                        ,moveType: 1 //拖拽模式，0或者1
                        ,content: '<div style="padding: 50px; line-height: 22px; background-color: #393D49; color: #fff; font-weight: 300;"><p style="text-align:center;color:yellow;"font-weight:bold;>小姐姐秒回哦</p><br><img src="uploads/goodsimgs/ma.png" alt=""></div>'
                        ,success: function(layero){
                            var btn = layero.find('.layui-layer-btn');
                            btn.find('.layui-layer-btn0').attr({
                                target: '_blank'
                            });
                        }
                    });
                }

            };

            $('.layui-btn').on('click', function(){
                var othis = $(this), method = othis.data('method');
                active[method] ? active[method].call(this, othis) : '';
            });
        });
    });
</script>
</body>
