<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title></title>
    <script src="{{asset('js/jquery-3.2.1.min.js')}}"></script>
    <script src="{{asset('js/ajaxfileupload.js')}}"></script>
    <script src="{{asset('js/mui.min.js')}}"></script>
    <link href="{{asset('css/mui.min.css')}}" rel="stylesheet"/>
    <script type="text/javascript" charset="utf-8">
        mui.init();
    </script>

</head>
<body>
<!-- 侧滑导航根容器 -->
<div class="mui-off-canvas-wrap mui-draggable mui-scalable">
    <!-- 主页面容器 -->
    <div class="mui-inner-wrap">
        <!-- 菜单容器 -->
        <aside class="mui-off-canvas-left">
            <div class="mui-scroll-wrapper">
                <div class="mui-scroll">
                    <!-- 菜单具体展示内容 -->
                    ...
                </div>
            </div>
        </aside>
        <!-- 主页面标题 -->
        <header class="mui-bar mui-bar-nav" style="background-color: rgba(254, 201, 16, 1);">
            <a class="mui-icon mui-action-menu mui-icon-bars mui-pull-left" style="color: #FFFFFF;"></a>
            <h1 class="mui-title" style="color: #FFFFFF;font-size: 22.5px;"><b>智能报修</b></h1>
        </header>
        <!-- 主页面内容容器 -->
        <div class="mui-content mui-scroll-wrapper">
            <div class="mui-scroll">
                <!-- 主界面具体展示内容 -->
                <div class="mui-card">
                    <div class="mui-card-content">
                        <div class="mui-card-content-inner">
                            <span style="">您是第一次进入该系统，请填写学号和手机号完成注册哦亲~~</span>
                        </div>
                    </div>
                </div>
                <div class="mui-card">
                    <div class="mui-card-header" style="color: #FFFFFF;background-color: rgba(254, 201, 16, 1);">填写信息</div>
                    <ul id="renwu" class="mui-table-view">
                        <!--注册面板-->
                        <li class="mui-table-view-cell" >
                            <form class="mui-input-group" style="background-color: rgba(255, 255, 255, 0);">

                                <div class="mui-input-row">
                                    <label>学号</label>
                                    <input id="number" type="text" class="mui-input-clear" placeholder="请填写学号"  >
                                </div>
                                <div class="mui-input-row">
                                    <label>手机号</label>
                                    <input id="telephone" type="text" class="mui-input-clear" placeholder="请填写手机号"  >
                                </div>
                                <div class="mui-button-row">
                                    <button id="reg" type="button" class="mui-btn mui-btn-yellow" data-loading-text="注册中" style="width:200px;background-color: rgba(254, 201, 16, 1);" >注册</button>
                                </div>

                            </form>
                        </li>
                        <!--任务模板-->
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<script>
    var openid = 'lalala';
    $("#reg").click(function(){
        var number=$("#number").val();
        var telephone=$("#telephone").val();
        mui("#reg").button('loading');
        alert(openid);
        $.ajax({
            url:'{{asset('/userEnter')}}',
            type:'POST',
            async:false,
            data:{openid:openid,number:number,telephone:telephone,state:1},
            dataType:'json',
            success:function(result){
                if(result['result']==1){
                    alert(result['remind']);
                    self.location = '{{asset('/user')}}';
                }else{
                    alert(result['remind']);
                }
                mui("#reg").button('reset');
            },
            error:function(){
                mui("#reg").button('reset');
                alert("请求出错");
            }
        });
    });




</script>
</html>