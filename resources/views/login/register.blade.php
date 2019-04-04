@include('layouts.layout')
<body>
<div class="maincont">
	<header>
		<a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
		<div class="head-mid">
			<h1>会员注册</h1>
		</div>
	</header>
	<div class="head-top">
		<img src="/shop/images/head.jpg" />
	</div><!--head-top/-->
	<form action="{{url('login/login')}}" method="get" class="reg-login">
		<h3>已经有账号了？点此<a class="orange" href="{{url('login/login')}}">登陆</a></h3>
		<div class="lrBox">
			<div class="lrList"><input type="text" placeholder="输入手机号码或者邮箱号" /></div>
			<div class="lrList2"><input type="text" placeholder="输入短信验证码" /> <button>获取验证码</button></div>
			<div class="lrList"><input type="text" placeholder="设置新密码（6-18位数字或字母）" /></div>
			<div class="lrList"><input type="text" placeholder="再次输入密码" /></div>
		</div><!--lrBox/-->
		<div class="lrSub">
			<input type="submit" value="立即注册" />
		</div>
	</form><!--reg-login/-->
	@include('layouts.footer')
</div><!--maincont-->
</body>
