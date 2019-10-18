<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title></title>
    <script src="{{asset('js/jquery-3.2.1.min.js')}}"></script>
</head>
<boday>
    <h1>跳转中...</h1>
</boday>
<script>
    window.onload(usertest());
    function usertest() {
        var openid = '789';
        $.ajax({
            url:'{{asset('/userEnter')}}',
            type:'POST',
            async:false,
            data:{
                openid:openid
            },
            dataType:'json',
            success:function(result){
                if(result['result']==1){
                    //alert("正在跳转至信息页...");
                    self.location = "{{asset('/user')}}";
                }else{
                    //alert("正在跳转至注册页面...");
                    self.location = "{{asset('/userregister')}}";
                }
                //$("#realdelete").button('reset');
            },
            error:function(){
                //$("#realdelete").button('reset');
                alert("请求出错");
            }
        });
    }
</script>
</html>