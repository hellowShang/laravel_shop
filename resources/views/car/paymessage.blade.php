@include('layouts.layout')
@if (session('status'))
 <div class="alert alert-success">
  <p style="text-align: center;font-weight: bold;font-size: 20px;">
   <font color="red" id="new">
    {{ session('status') }}
   </font>
  </p>
 </div>
@endif

<script>
    $(function(){
        layui.use('layer',function(){
            var layer = layui.layer;
            var message = $('#new').text();
            if(message != ''){
                setTimeout('location.href = "/"',3000);
            }
        });
    });
</script>