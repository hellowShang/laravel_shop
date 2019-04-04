@include('layouts.layout')
  <body>
    <div class="maincont">
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>收货地址</h1>
      </div>
     </header>
     <div class="head-top">
      <img src="/shop/images/head.jpg" />
     </div><!--head-top/-->
     <table class="shoucangtab">
      <tr>
       <td width="75%"><a href="{{url('user/addressadd')}}" class="hui"><strong class="">+</strong> 新增收货地址</a></td>
       <td width="25%" align="center" style="background:#fff url(images/xian.jpg) left center no-repeat;">
        <a href="javascript:;" id="alldel" class="orange">删除信息</a>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <input type="checkbox" id="allbox">全选
       </td>
      </tr>
     </table>
     
     <div class="dingdanlist">
      <table>
       @foreach($addressInfo as $k=>$v)
         @if($v['is_default'] == 1)
         <tr bgcolor="#dcdcdc" goods_id = "{{$v['address_id']}}">
          <td>
           <p><input type="checkbox" class="box">{{$k+1}}</p>
          </td>
          <td width="50%">
           <h3> <font color="red">{{$v['address_name']}}</font>&nbsp;&nbsp;&nbsp;&nbsp;{{$v['address_tel']}}</h3>
           <time>{{$v['province']->name}}{{$v['city']->name}}{{$v['area']->name}}{{$v['address_add']}}</time>
          </td>
          <td align="right"><a href="{{url('user/addressupd')}}/{{$v['address_id']}}" class="hui"><span class="glyphicon glyphicon-check"></span> 修改信息</a></td>
         </tr>
         @else
         <tr goods_id = "{{$v['address_id']}}">
          <td>
           <p><input type="checkbox" class="box">{{$k+1}}</p>
          </td>
          <td width="50%">
           <h3> <font color="red">{{$v['address_name']}}</font>&nbsp;&nbsp;&nbsp;&nbsp;{{$v['address_tel']}}</h3>
           <time>{{$v['province']->name}}{{$v['city']->name}}{{$v['area']->name}}{{$v['address_add']}}</time>
          </td>
          <td align="right"><a href="{{url('user/addressupd')}}/{{$v['address_id']}}" class="hui"><span class="glyphicon glyphicon-check"></span> 修改信息</a></td>
         </tr>
         @endif
        @endforeach
      </table>
     </div><!--dingdanlist/-->
     @include('layouts.footer')
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
            var layer = layui.layer;
            // 全选
            $("#allbox").click(function(){
                var checked = $(this).prop('checked');
                $(".box").prop('checked',checked);

            });

            // 删除
            $('#alldel').click(function(){
                // 复选框是否选中
                var goods_id = '';
                $('.box').each(function(index){
                    if($(this).prop('checked') == true){
                        goods_id += $(this).parents('tr').attr('goods_id')+',';
                        // jq 删除
                        $(this).parents("tr").remove();
                    }
                });
                goods_id = goods_id.substr(0,goods_id.length-1);
                console.log(goods_id);
                if(goods_id == ''){
                    layer.msg('至少选择一个地址才能删除',{icon:5});
                    return false;
                }
                $.post(
                    "/car/clearCar",
                    {goods_id: goods_id,type:3},
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
        });
    });
   </script>
  </body>
