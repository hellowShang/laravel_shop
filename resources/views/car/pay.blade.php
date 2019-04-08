 @include('layouts.layout')
 <style>
    .address{
      border-bottom:1px solid red;
    }
    .pay_type{
     border: 2px solid red;
    }
 </style>
  <body>
    <div class="maincont">
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>购物车</h1>
      </div>
     </header>
     <div class="head-top">
      <img src="/shop/images/head.jpg" />
     </div><!--head-top/-->
     <div class="dingdanlist">
      <table>
       <tr>
        <td class="dingimg" width="75%" colspan="3" align="center"><a href="/user/addressadd"><font>新增收货地址</font></a></td>
       </tr>
       <tr><td colspan="3" style="height:10px; background:#efefef;padding:0;"></td></tr>
       <tr>
        <td class="dingimg" width="75%" colspan="2">选择收货时间</td>
        <td align="right" class="address_time"><input type="datetime-local"></td>
       </tr>
       <tr><td colspan="3" style="height:10px; background:#efefef;padding:0;"></td></tr>
       <tr>
        <td class="dingimg" width="75%" colspan="2">支付方式</td>
        <td align="right" class="checked">
         <span style="cursor: pointer;" pay_type="1"><b class="hui" style="color: black">余额</b></span>
         <span class="pay_type" style="cursor: pointer;" pay_type="2"><b class="hui" style="color: black">支付宝</b></span>
         <span style="cursor: pointer;" pay_type="3"><b class="hui" style="color: black">银行卡</b></span>
        </td>
       </tr>
       <tr><td colspan="3" style="height:10px; background:#efefef;padding:0;"></td></tr>
       <tr>
        <td class="dingimg" width="75%" colspan="2">优惠券</td>
        <td align="right"><span class="hui">无</span></td>
       </tr>
       <tr><td colspan="3" style="height:10px; background:#efefef;padding:0;"></td></tr>
       @foreach($addressInfo as $v)
       <tr>
        <td id="address" address_id="{{$v['address_id']}}" style="color: red;font-weight: bold;font-size: 16px;" class="dingimg" width="75%" colspan="3">收货地址</td>
       </tr>
       <tr>
        <td class="address" >收货人：</td>
        <td class="address" colspan="2">{{$v['address_name']}}</td>
       </tr>
       <tr>
        <td class="address" >详细地址：</td>
        <td class="address" colspan="2">
         {{$v['province']->name}}{{$v['city']->name}}{{$v['area']->name}}{{$v['address_add']}}
        </td>
       </tr>
       <tr>
        <td class="address" >联系方式</td>
        <td class="address" colspan="2">{{$v['address_tel']}}</td>
       </tr>
       @endforeach
       <tr><td colspan="3" style="height:10px; background:#fff;padding:0;"></td></tr>
       <tr>
        <td class="dingimg" width="75%" colspan="3" style="color: red;font-weight: bold;font-size: 16px;">商品清单</td>
       </tr>
       @foreach($cartInfo as $k=>$v)
       <tr class="goods_id" goods_id="{{$v->goods_id}}">
        <td class="dingimg" width="15%"><img src="/uploads/goodsimgs/{{$v->goods_img}}" /></td>
        <td width="50%">
         <h3>{{$v->goods_name}}</h3>
         <time>下单时间：{{date('Y-m-d H:i:s',time())}}</time>
        </td>
        <td align="right"><span class="qingdan">X {{$v->buy_number}}</span></td>
       </tr>
       <tr>
        <th colspan="3"><strong class="orange">¥{{$v->buy_number*$v->self_price}}</strong></th>
       </tr>
       @endforeach
       <tr>
        <td class="dingimg" width="75%" colspan="2">商品金额</td>
        <td align="right"><strong class="orange">¥{{$count}}</strong></td>
       </tr>
       <tr>
        <td class="dingimg" width="75%" colspan="2">折扣优惠</td>
        <td align="right"><strong class="green">¥0.00</strong></td>
       </tr>
       <tr>
        <td class="dingimg" width="75%" colspan="2">抵扣金额</td>
        <td align="right"><strong class="green">¥0.00</strong></td>
       </tr>
      </table>
     </div><!--dingdanlist/-->
     
     
    </div><!--content/-->
    
    <div class="height1"></div>
    <div class="gwcpiao">
     <table>
      <tr>
       <th width="10%"><a href="javascript:;"></a></th>
       <td width="50%">总计：<strong class="orange">¥{{$count}}</strong></td>
       <td width="40%"><a href="javascript:;" class="jiesuan">提交订单</a></td>
      </tr>
     </table>
    </div><!--gwcpiao/-->
   <script>
    $(function(){
        $('.spinnerExample').spinner({});
        layui.use('layer',function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // 选择支付方式
            $('.checked').children().click(function () {
                    $(this).addClass('pay_type');
                    $(this).siblings().removeClass('pay_type');
            });

            // 订单提交
            $('.jiesuan').click(function () {
                // 获取商品id
                var _tr = $(".goods_id");
                var goods_id = '';
                _tr.each(function(index){
                    goods_id +=$(this).attr('goods_id')+',';
                });
                if(goods_id == ''){
                    layer.msg('没有商品，不能提交订单哦',{icon:5});
                    return false;
                }
                goods_id = goods_id.substr(0,goods_id.length-1);

                // 获取收货地址id
                var address_id = $("#address").attr('address_id');
                // console.log(address_id);

                //获取支付方式
                var pay_type = $(".pay_type").attr('pay_type');

                // 选择收货时间
                var address_time = $('.address_time').children().val();

                // 订单提交
                $.post(
                    '/car/submitPay',
                    {goods_id:goods_id,address_id:address_id,pay_type:pay_type,address_time:address_time},
                    function(res){
                        // console.log(res);
                        if(res.code == 6){
                            layer.msg(res.font,{icon:res.code},function(){
                                location.href = '/car/success';
                            });
                        }else{
                            layer.msg(res.font,{icon:res.code});
                        }
                    },
                    'json'
                );
            });
        });
    });
	</script>
  </body>
