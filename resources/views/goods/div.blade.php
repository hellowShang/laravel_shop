@foreach($goodsInfo as $v)
    <dl>
        <dt><a href="{{url('goods/goodsDetail')}}/{{$v->goods_id}}"><img src="/uploads/goodsimgs/{{$v->goods_img}}" width="100" height="100" /></a></dt>
        <dd>
            <h3><a href="{{url('goods/goodsDetail')}}/{{$v->goods_id}}">{{$v->goods_name}}</a></h3>
            <div class="prolist-price"><strong>¥{{$v->self_price}}</strong> <span>¥{{$v->market_price}}</span></div>
            <div class="prolist-yishou"><span>5.0折</span> <em>销量：{{$v->goods_num}}</em></div>
        </dd>
        <div class="clearfix"></div>
    </dl>
@endforeach