 @include('layouts.layout')
  <body>
    <div class="maincont">
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>我的收藏</h1>
      </div>
     </header>
     <div class="head-top">
      <img src="/shop/images/head.jpg" />
     </div><!--head-top/-->
     <table class="shoucangtab">
      <tr>
       <td width="75%"><span class="hui">收藏栏共有：<strong class="orange">{{$count}}</strong>件商品</span></td>
       <td width="25%" align="center" style="background:#fff url(/shop/images/xian.jpg) left center no-repeat;"><a href="javascript:;" class="orange" id="del">全部取消</a></td>
      </tr>
     </table>
     @foreach($collect as $v)
     <div class="dingdanlist">
      <table>
       <tr>
        <td colspan="2" width="65%"></td>
        <td width="35%" align="right"><div class="qingqu"><a href="javascript:;" class="orange" goods_id = "{{$v->goods_id}}"><font class="del">取消收藏</font></a></div></td>
       </tr>
       <tr>
        <td class="dingimg" width="15%"><img src="/uploads/goodsimgs/{{$v->goods_img}}" /></td>
        <td width="50%">
         <h3>{{$v->goods_name}}</h3>
        </td>
        <td align="right"><img src="/shop/images/jian-new.png" /></td>
       </tr>
       <tr>
        <th colspan="3"><strong class="orange">¥{{$v->self_price}}</strong></th>
       </tr>
      </table>
     </div>
      @endforeach
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

            // 删除收藏
            $(document).on('click','.del',function(){
                var goods_id = $(this).parent().attr('goods_id');
                // console.log(goods_id);
                $.post(
                    '/user/collectDel',
                    {goods_id:goods_id},
                    function(res){
                        // console.log(res);
                        if(res.code == 6){
                            layer.msg(res.font,{icon:res.code},function(){
                                history.go(0);
                            });
                        }else{
                            layer.msg(res.font,{icon:res.code});
                        }
                    },
                    'json'
                );
            });

            // 全部删除
            $(document).on('click','#del',function(){
                $.post(
                    '/user/collectDel',
                    function(res){
                        // console.log(res);
                        if(res.code == 6){
                            layer.msg(res.font,{icon:res.code},function(){
                                history.go(0);
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
</html>