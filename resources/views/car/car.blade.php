    @include('layouts.layout')
    <style>
     .pp a{
      color: gray;
      text-decoration: underline;
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
     <table class="shoucangtab">
      <tr>
       <td width="75%"><span class="hui">购物车共有：<strong id="goodsnum" class="orange">{{$count}}</strong>件商品</span></td>
       <td width="25%" align="center" style="background:#fff url(images/xian.jpg) left center no-repeat;">
        <span class="glyphicon glyphicon-shopping-cart" style="font-size:2rem;color:#666;"></span>
       </td>
      </tr>
     </table>
      <span width="100%" colspan="4"><a href="javascript:;"><input id="allbox" type="checkbox" name="1" /> 全选</a></span>

      <span style="float: right;"><a id="alldel" href="javascript:;">清空购物车</a></span>
     @if($cartInfo)
      @foreach($cartInfo as $k=>$v)
     <div class="dingdanlist">
      <table>
       <tr goods_id="{{$v->goods_id}}">
        <td width="4%"><input class="box" type="checkbox" name="1" /></td>
        <td class="dingimg" width="15%"><img src="{{$path}}/{{$v->goods_img}}" /></td>
        <td width="50%">
         <h3>{{$v->goods_name}}</h3>
         <time>加入购物车时间：{{date('Y-m-d H:i:s',$v->update_time)}}</time>
        </td>
        <td align="right">
         <span style="color: red;font-weight: bold;">{{$v->buy_number}}件</span>&nbsp;&nbsp;
         <span class="pp"><a  href="/goods/goodsDetail/{{$v->goods_id}}">不够？</a></span>
        </td>
       </tr>
       <tr>
        <td></td>
        <td></td>
        <td align="right">小计：<strong class="orange">¥<span>{{$v->buy_number*$v->self_price}}</span></strong></td>
        <td align="right"><a class="del" href="javascript:;">删除</a></td>
       </tr>
      </table>
     </div>
      @endforeach
     @else
       <div class="dingdanlist">
        <p style="color: red;font-weight: bold;text-align: center;font-size: 20px;">购物车空空如也，快去添加吧</p>
       </div>
     @endif
     <div class="height1"></div>
     <div class="gwcpiao">
     <table>
      <tr>
       <th width="10%"><a href="javascript:history.back(-1)"><span class="glyphicon glyphicon-menu-left"></span></a></th>
       <td width="50%">总计：<strong class="orange" >¥<span id="count">0</span></strong></td>
       <td width="40%"><a href="javascript:;" id="sub" class="jiesuan">去结算</a></td>
      </tr>
     </table>
    </div><!--gwcpiao/-->
    </div><!--maincont-->

     <script>
      $(function(){

          $('.spinnerExample').spinner({});
          $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
          });

          layui.use('layer',function(){
              // 全选
              $("#allbox").click(function(){
                  var checked = $(this).prop('checked');
                  $(".box").prop('checked',checked);

                  // 获取总价
                  getCountPrice()
              });

              // 复选框
              $(document).on('click','.box',function(){
                  // 获取总价
                  getCountPrice()
              });

              // 删除
              $(document).on('click','.del',function(){
                  var goods_id = $(this).parents('tr').prev().attr("goods_id");
                  $.post(
                      "/car/clearCar",
                      {goods_id: goods_id,type:1},
                      function(res){
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

              // 清空购物车
              $('#alldel').click(function(){
                  // 复选框是否选中
                  var goods_id = '';
                  $('.box').each(function(index){
                      if($(this).prop('checked') == true){
                          goods_id += $(this).parents('tr').attr('goods_id')+',';
                          // jq 删除
                          $(this).parents("div[class='dingdanlist']").remove();
                      }
                  });
                  goods_id = goods_id.substr(0,goods_id.length-1);
                  if(goods_id == ''){
                      layer.msg('至少选择一件商品才能清空',{icon:5});
                      return false;
                  }
                  $.post(
                      "/car/clearCar",
                      {goods_id: goods_id,type:2},
                      function(res){
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

              // 提交
              $("#sub").click(function(){
                  // 复选框是否选中
                  var goods_id = '';
                  $('.box').each(function(index){
                      if($(this).prop('checked') == true){
                          goods_id += $(this).parents('tr').attr('goods_id')+',';
                      }
                  });
                  goods_id = goods_id.substr(0,goods_id.length-1);
                  if(goods_id == ''){
                      layer.msg('至少选择一件商品才能进行结算',{icon:5});
                      return false;
                  }
                  // console.log(goods_id);
                  location.href = "/car/pay/"+goods_id;
              });

              // 获取总价
              function getCountPrice(){
                  // 获取选中的复选框的id
                  var goods_id = '';
                  $('.box').each(function(index){
                      if($(this).prop('checked') == true){
                          goods_id += $(this).parents('tr').attr('goods_id')+',';
                      }
                  });
                  goods_id = goods_id.substr(0,goods_id.length-1)
                  // console.log(goods_id);
                  $.post(
                      "/car/getCountPrice",
                      {goods_id:goods_id},
                      function(res){
                          // console.log(res);
                          $('#count').text(res);
                      }
                  );
              }

          });
      });
     </script>
  </body>
