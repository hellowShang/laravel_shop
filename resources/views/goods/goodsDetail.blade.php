    @include('layouts.layout')
  <body>
  <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="maincont">
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl">
       <span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>产品详情</h1>
      </div>
     </header>
     <div id="sliderA" class="slider">
      @foreach($info->goods_imgs as $k=>$v)
       <img src="/uploads/goodsimgs/{{$v}}" />
      @endforeach
     </div><!--sliderA/-->
     <table class="jia-len">
      <tr>
       <th><strong class="orange">{{$info->goods_name}}</strong></th>
       <td>
        &nbsp;&nbsp;
       </td>
       <td>
        <input type="text" id="value" class="spinnerExample" />
       </td>
      </tr>
      <tr>
       <td>
            <font color="red">单价：￥{{$info->self_price}}</font><br>
            <strong>{{$info->goods_desc}}</strong>
       </td>
       <td align="right">
        <button id="cart" goods_id = "{{$info->goods_id}}" goods_num="{{$info->goods_num}}" style="margin-left:45px;background-color:orange;border: none;border: 2px solid red;font-size: 20px;">加入购物车</button>
       </td>
       <td align="right">
        <a href="javascript:;" class="shoucang">
         <span style="font-weight: bold;color: #000;" id="collect">@if($collect)★@else☆@endif</span>
        </a>
       </td>
      </tr>
     </table>
     <div class="height2"></div>
     <h3 class="proTitle">商品热度</h3>
     <ul class="guige">
      <li><a href="javascript:;">@if($info->is_up)上架@else未上架@endif</a></li>
      @if($info->is_new)<li><a href="javascript:;">新品</a></li>@endif
      @if($info->is_best)<li><a href="javascript:;">精品</a></li>@endif
      @if($info->is_hot)<li><a href="javascript:;">热卖</a></li>@endif
      <div class="clearfix"></div>
     </ul><!--guige/-->
     <div class="height2"></div>
     <div class="zhaieq">
      <a href="javascript:;" class="zhaiCur">商品简介</a>
      <a href="javascript:;">商品参数</a>
      <a href="javascript:;" style="background:none;">订购列表</a>
      <div class="clearfix"></div>
     </div><!--zhaieq/-->
     <div class="proinfoList">
      <img src="/uploads/goodsimgs/{{$info->goods_img}}" width="636" height="822" />
     </div><!--proinfoList/-->
     <div class="proinfoList">
      暂无信息....
     </div><!--proinfoList/-->
     <div class="proinfoList">
      暂无信息......
     </div><!--proinfoList/-->
    </div><!--maincont-->
    @include('layouts.footer')
   <script>
       $(function () {
           $("#sliderA").excoloSlider();
           $('.spinnerExample').spinner({});
           $.ajaxSetup({
               headers: {
                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               }
           });
           layui.use('layer',function(){
               // 加入购物车
               $(document).on('click','#cart',function(){
                   var value = $("#value").val();
                   var goods_id = $(this).attr('goods_id');
                   var goods_number = $(this).attr('goods_num');
                   // console.log(goods_number);
                   $.post(
                       '/car/cartAdd',
                       {buy_number:value,goods_id:goods_id,goods_num:goods_number},
                       function(res){
                           layer.msg(res.font,{icon:res.code});
                       },
                       'json'
                   );
               });

               // 收藏
               $('#collect').click(function(){
                   var _this = $(this);
                   var  value = _this.text();
                   if(value == '★'){
                       layer.msg('已经收藏过了，不能重复收藏啦',{icon:0});
                       return false;
                   }
                   var goods_id = _this.parents('td').prev().children().attr('goods_id');
                   $.post(
                       '/goods/collect',
                       {goods_id: goods_id},
                       function(res){
                           if(res.code == 6){
                               layer.msg(res.font,{icon:res.code});
                               _this.text('★')
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
