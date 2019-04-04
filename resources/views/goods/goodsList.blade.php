   @include('layouts.layout')
  <body>

    <div class="maincont">
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <form action="#" method="get" class="prosearch"><input type="text" /></form>
      </div>
     </header>
     <ul class="pro-select">
      <li class="pro-selCur li"><a href="javascript:;" field="is_new">新品</a></li>
      <li class="li"><a href="javascript:;" field="goods_num">销量</a></li>
      <li class="li"><a href="javascript:;" field="self_price">价格</a></li>
     </ul><!--pro-select/-->
     <div class="prolist">
      @foreach($goodsInfo as $v)
      <dl>
       <dt><a href="{{url('goods/goodsDetail')}}/{{$v->goods_id}}"><img src="{{$path}}/{{$v->goods_img}}" width="100" height="100" /></a></dt>
       <dd>
        <h3><a href="{{url('goods/goodsDetail')}}/{{$v->goods_id}}">{{$v->goods_name}}</a></h3>
        <div class="prolist-price"><strong>¥{{$v->self_price}}</strong> <span>¥{{$v->market_price}}</span></div>
        <div class="prolist-yishou"><span>5.0折</span> <em>销量：{{$v->goods_num}}</em></div>
       </dd>
       <div class="clearfix"></div>
      </dl>
       @endforeach
     </div><!--prolist/-->
     @include('layouts.footer')
    </div><!--maincont-->
    <script>
        $(function () {
           $("#sliderA").excoloSlider();
           $(".li").click(function(){
               $.ajaxSetup({
                   headers: {
                       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                   }
               });
               $(this).prop('class','pro-selCur');
               $(this).siblings().prop('class','');
               var field = $(this).children().attr('field');
               $.post(
                   '/goods/getGoodsInfo',
                   {field:field},
                   function(res){
                       $(".prolist").html(res);
                   }
               );
           });
        });
	</script>
  </body>
