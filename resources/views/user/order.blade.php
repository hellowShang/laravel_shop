 @include('layouts.layout')
  <body>
    <div class="maincont">
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>我的订单</h1>
      </div>
     </header>
     <div class="head-top">
      <img src="/shop/images/head.jpg" />
     </div><!--head-top/-->
     
     <div class="zhaieq oredereq">
      <a href="javascript:;" class="zhaiCur" status="1"><font class="status">待付款</font></a>
      <a href="javascript:;" status="2"><font class="status">待发货</font></a>
      <a href="javascript:;" status="3"><font class="status">已取消</font></a>
      <a href="javascript:;" style="background:none;" status="4"><font class="status">已完成</font></a>
      <div class="clearfix"></div>
     </div><!--oredereq/-->
     <div class="unset">
      @foreach($orderInfo as $k=>$v)
       <div class="dingdanlist">
        <table>
         <tr>
          <td colspan="2" width="65%">订单号：<strong>{{$v->order_no}}</strong></td>
          <td width="35%" align="right"><div class="qingqu"><a href="javascript:;" class="orange">订单取消</a></div></td>
         </tr>
         <tr>
          <td class="dingimg" width="15%"><img src="/uploads/goodsimgs/{{$v->goods_img}}" /></td>
          <td width="50%">
           <h3>{{$v->goods_name}}</h3>
           <time>下单时间：{{$v->create_time}}</time>
          </td>
          <td align="right"><img src="/shop/images/jian-new.png" /></td>
         </tr>
         <tr>
          <th colspan="3"><strong class="orange">¥{{$v->order_amount}}</strong></th>
         </tr>
        </table>
       </div><!--dingdanlist/-->
      @endforeach
     </div>
     @include('layouts.footer')
    </div><!--maincont-->
   <script>
	$('.spinnerExample').spinner({});
    $(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        layui.use('layer',function(){
            var layer = layui.layer;

            // 状态  重新获取
            $(document).on('click','.status',function(){
                var status = $(this).parent().attr('status');
                // console.log(status);
                $.post(
                    '/user/unset',
                    {status:status},
                    function(res){
                        // console.log(res);
                        $('.unset').html(res);
                    },
                );
            });

        });
    });
   </script>
  </body>
