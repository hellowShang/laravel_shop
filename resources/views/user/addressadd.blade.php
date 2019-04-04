    @include('layouts/layout')
    <style>
     #is_default{
       width: 200px;
      height: 50px;
      background: #f00;
      color: #fff;
      font-size: 12px;
      font-family: 新宋体;
      font-weight: bold;
      text-align: center;
      line-height: 50px;
      float: right;
     }
    </style>
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
     <form action="" method="post" onsubmit="return false;" class="reg-login">
      <div class="lrBox">
       <div class="lrList"><input type="text" name="address_name" id="address_name" placeholder="收货人" /></div>
       <div class="lrList"><input type="text" name="address_mail" id="address_mail" placeholder="邮编" /></div>
        <select name="province" class="ads" id="province">
         <option selected value="">省份/直辖市/自治区</option>
         @foreach($province as $k=>$v)
         <option style="color:black;" value="{{$v->id}}">{{$v->name}}</option>
          @endforeach
        </select>
        <select name="city" class="ads" id="city">
         <option value="">市/自治州</option>
        </select>
        <select name="area" class="ads" id="area">
         <option value="">县/区</option>
        </select>
       <div class="lrList"><input type="text" name="address_add" id="address_add" placeholder="详细地址" /></div>
       <div class="lrList"><input type="text" name="address_tel" id="address_tel" placeholder="手机号" /></div>
       <div class="lrList2" style="border:none;"><input type="button" id="is_default" is_default="" value="设为默认"></div>
      </div><!--lrBox/-->
      <div class="lrSub">
       <input type="submit" id="sub" value="保存" />
      </div>
     </form><!--reg-login/-->
     
    @include('layouts.footer')
    </div>
   <script>
    $(function () {
        $('.spinnerExample').spinner({});

        layui.use('layer',function(){
            var layer = layui.layer;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // 下拉菜单的三级联动
            $(document).on('change','.ads',function(){
                var _this = $(this);
                var id = _this.val();
                // console.log(id);
                var _option = "<option value=''>--请选择--</option>";
                _this.nextAll('select').html(_option);
                $.post(
                    "/user/getArea",
                    {id:id},
                    function(res){
                        // console.log(res);
                        if(res.code == 6){
                            for(var i in res['areaInfo']){
                                _option += "<option style=\"color:black;\" value='"+res['areaInfo'][i]['id']+"'>"+res['areaInfo'][i]['name']+"</option>";
                            }
                            _this.next().html(_option);
                        }else{
                            layer.msg(res.font,{icon:res.code});
                        }
                    },
                    'json'
                );
            });

            // 设为默认
            $('#is_default').click(function () {
                var is_default = $(this).attr('is_default');
                if(is_default == ''){
                    $(this).attr('is_default','1');
                    $(this).css('background-color','green');
                }
            });

            // 收货地址数据提交
            $(document).on('click','#sub',function(){
                // 获取所有数据提交给控制器
                var obj ={};
                obj.address_name = $('#address_name').val();
                obj.address_tel = $('#address_tel').val();
                obj.province = $('#province').val();
                obj.city = $('#city').val();
                obj.area = $('#area').val();
                obj.address_add = $('#address_add').val();
                obj.address_mail = $('#address_mail').val();

                // 检测是否设置为默认地址
                var is_default = $('#is_default').attr('is_default');
                if(is_default == 1){
                    obj.is_default = 1;
                }else{
                    obj.is_default = 2;
                }
                // console.log(obj);
                $.post(
                    "/user/addressadd",
                    obj,
                    function(res){
                        // console.log(res);
                        if(res.code == 6){
                            layer.msg(res.font,{icon:res.code},function(){
                                location.href='/user/address';
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
