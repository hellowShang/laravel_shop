    @include('layouts.layout')
  <body>
    @if (session('status'))
        <div class="alert alert-success">
            <p style="text-align: center;font-weight: bold;font-size: 20px;"><font color="red" id="new">{{ session('status') }}</font></p>
        </div>
    @else
        <div class="maincont">
            <header>
                <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
                <div class="head-mid">
                    <h1>购物车</h1>
                </div>
            </header>
            <div class="susstext">订单提交成功</div>
            <div class="sussimg">&nbsp;</div>
            <div class="dingdanlist">
                <table>
                    <tr>
                        <td width="50%">
                            <h3>订单号：{{$orderInfo->order_no}}</h3>
                            <time>创建日期：{{date('Y-m-d H:i:s',$orderInfo->create_time)}}<br />
                                @if($orderInfo->order_text != '')
                                    失效日期：{{$orderInfo->order_text}}:00
                                @else
                                @endif
                            </time>
                            <strong class="orange">¥{{$orderInfo->order_amount}}</strong>
                        </td>
                        <td align="right"><span class="orange">等待支付</span></td>
                    </tr>
                </table>
            </div><!--dingdanlist/-->
            <div class="succTi orange">请您尽快完成付款，否则订单将被取消</div>

        </div><!--content/-->

        <div class="height1"></div>
        <div class="gwcpiao">
            <table>
                <tr>
                    <td width="50%"><a href="{{url('goods/goodsList')}}" class="jiesuan" style="background:#5ea626;">继续购物</a></td>
                    <td width="50%"><a href="/car/alipay/{{$orderInfo->order_no}}" order_no = "{{$orderInfo->order_no}}" class="jiesuan">立即支付</a></td>
                </tr>
            </table>
        </div><!--gwcpiao/-->
    @endif
   <script>
       $(function(){
           $('.spinnerExample').spinner({});
           var value = $('#new').text();
           if(value != ''){
               time = setTimeout("history.go(0)",3000);
           }
       });
	</script>
  </body>
