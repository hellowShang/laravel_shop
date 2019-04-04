@include('layouts.layout')
<body>
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="maincont">
	<header>
		<a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
		<div class="head-mid">
			<h1>会员登录</h1>
		</div>
	</header>
	<div class="head-top">
		<img src="/shop/images/head.jpg" />
	</div><!--head-top/-->
	<form class="reg-login" onsubmit="return false">
		@csrf
		<h3>还没有三级分销账号？点此<a class="orange" href="{{url('login/register')}}">注册</a></h3>
		<div class="lrBox">
			<div class="lrList"><input type="text" name="account" id="account" placeholder="输入手机号码或者邮箱号" /></div>
			<div class="lrList"><input type="password" name="pwd" id="pwd" placeholder="输入密码" /></div>
		</div><!--lrBox/-->
		<div class="lrSub">
			<input type="submit" value="立即登录" />
		</div>
	</form><!--reg-login/-->
	@include('layouts.footer')
</div><!--maincont-->
</body>
<script>
	$(function(){
	    layui.use('layer',function(){
	        var layer = layui.layer;

            $("input[type='submit']").click(function () {
                var account = $('#account').val();
                var pwd = $('#pwd').val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.post(
                    '/login/login',
                    {account:account,pwd:pwd},
                    function(res){
						layer.msg(res.font,{icon:res.code});
						if(res.code == 6){
						    location.href = '/';
						}
                    },
                    'json'
                );
            });
		});
	})
</script>
