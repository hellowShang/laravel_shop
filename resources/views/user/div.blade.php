@foreach($orderInfo as $k=>$v)
  <div class="dingdanlist">
   <table>
    <tr>
     <td colspan="2" width="65%">订单号：<strong>{{$v->order_no}}</strong></td>
     <td width="35%" align="right"><div class="qingqu"><a href="javascript:;" class="orange">订单取消</a></div></td>
    </tr>
    <tr>
     <td class="dingimg" width="15%"><img src="{{$path}}/{{$v->goods_img}}" /></td>
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
