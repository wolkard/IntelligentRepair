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

                    <div class="mui-card-header" style="color: #FFFFFF;background-color: rgba(254, 201, 16, 1);">用户信息</div>

                    <div class="mui-card-content">
                        <div class="mui-card-content-inner">

                            <span style="">电话:<span id = "telephoneInHtml"></span></span>
                            <span style="">学号:<span id = "numberInHtml"></span></span>

                        </div>
                    </div>

                </div>

                删除：<br/>
               <form method="post" action="/delectItem">
                   <input type="text" name="category_id[0]" placeholder="category_id">
                   <input type="submit" id="testbutton" value="提交" >
               </form>

                <form method="post" action="/changeItemInformation">
                   <input type="text" name="category_ids[0]" placeholder="category_ids">
                   <input type="text" name="attribute_names[0]" placeholder="attribute_names">
                   <input type="text" name="large_category_names[0]" placeholder="large_category_names">
                   <input type="text" name="small_category_names[0]" placeholder="small_category_names">
                   <input type="text" name="items[0]" placeholder="items">
                   <input type="text" name="descriptions[0]" placeholder="descriptions">
                   <input type="submit" id="testbutton" value="提交" >
               </form>
                <div class="mui-card">
                    <div class="mui-card-content">
                        <div class="mui-card-content-inner" style="height:62px;">

                            <button onclick="createNewTask(1)" class="mui-btn mui-btn-yellow" style="position:absolute;left:16%;background-color: rgba(254, 201, 16, 1);">
                                新建报修
                            </button>
                            <button onclick="createNewTask(2)" class="mui-btn mui-btn-yellow" style="position:absolute;left:60%;background-color: rgba(254, 201, 16, 1);">
                                查看任务
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<script>

    //	需要ajax的地方：
    //	1.打开这个页面的时候得到用户的信息.信息具体是什么请对应数据库改代码43行

    //  函数功能：修改用户信息
    function deleteCategory(){
        var category_id = ;

        alert(category_id[0]);
        $.ajax({
            url:'{{asset('/delectItem')}}',
            type:'POST',
            async:false,
            data:{
                category_id:category_id[0]
            },
            dataType:'json',
            success:function(result){
                if(result['result']==1){
                    alert(result['remind']);
                }else{
                    alert(result['remind']);
                }
                //$("#realdelete").button('reset');
            },
            error:function(){
                //$("#realdelete").button('reset');
                alert("请求出错");
            }
        });
    }

    function usertest(){
        $.ajax({
            url:'{{asset('/userEnter')}}',
            type:'POST',
            async:false,
            data:{openid:openid},
            dataType:'json',
            success:function(result){
                if(result['result']==0){
                    self.location = '{{asset('/userregister')}}';
                }else{
                    getUserInfo();
                }
                //$("#realdelete").button('reset');
            },
            error:function(){
                //$("#realdelete").button('reset');
                alert("请求出错");
            }
        });
    }

    //  函数功能：用户信息
    function getUserInfo(){
        alert(openid);
        $.ajax({
            url:'{{asset('/findUserInformation')}}',
            type:'POST',
            async:false,
            data:{openid:openid},
            dataType:'json',
            success:function(result){
                if(result['result']==1){
                    alert(result['data']['telephone']);
                    $("#telephoneInHtml").html(result['data']['telephone']);
                    $("#numberInHtml").html(result['data']['number']);
                }else{
                    alert("获取用户信息失败！");
                }
                //$("#realdelete").button('reset');
            },
            error:function(){
                //$("#realdelete").button('reset');
                alert("请求出错");
            }
        });
    }

    //  函数功能:删除用户（这个没必要吧- -葛操说）

    //	函数功能：获取周围所有WiFi信息
    function getMac() {
        if (plus.os.name == "Android") {
            //WifiManager
            var Context = plus.android.importClass("android.content.Context");
            var WifiManager = plus.android.importClass("android.net.wifi.WifiManager");
            var wifiManager = plus.android.runtimeMainActivity().getSystemService(Context.WIFI_SERVICE);
            mac_intensity=wifiManager.getScanResults();
            return mac_intensity;
        }
    }

    //函数功能：调用手机摄像头拍照并上传
    function getImage(){
        var strFolder="/storage/emulated/0/DCIM/getImage/Camera/";
        var File = plus.android.importClass("java.io.File");
        var fd = new File(strFolder);
        if(!fd.exists()){
            fd.mkdirs();
            plus.nativeUI.toast("已创建目录");
        }
        var cmr = plus.camera.getCamera();
        var res = cmr.supportedImageResolutions[0];
        var fmt = cmr.supportedImageFormats[0];
        console.log("Resolution: "+res+", Format: "+fmt);
        cmr.captureImage( function( path ){
                //上传图片到服务器……
            },
            function( error ) {
                plus.nativeUI.toast("调用失败");
            },
            {filename:strFolder,resolution:res,format:fmt}
        );
    }

    //事件函数：当提交按钮被点击，提交表单
    $("#wifipost").click(function(){
        maclevel=SSIDs.toString();
        mui(this).button('loading');
        mui.ajax('{{asset('http://server-name/login.php')}}',{
            data:{
                mac_intensity:maclevel,
                large_area_name:$("#large_area_name").value,
                part_area_name:$("#part_area_name").value,
                building:$("#building").value,
                floor:$("#floor").value,
                room:$("#room").value
            },
            dataType:'json',//服务器返回json格式数据
            type:'post',//HTTP请求类型
            timeout:10000,//超时时间设置为10秒；
            //headers:{'Content-Type':'application/json'},
            success:function(data){
                if(data=1){
                    plus.nativeUI.toast('上传成功');
                    mui("#wifipost").button('reset');
                }

            },
            error:function(xhr,type,errorThrown){
                //异常处理；
                console.log(type);
                mui("#wifipost").button('reset');
                plus.nativeUI.toast('请求超时或异常');
            }
        });

    });

    //打开子页面新建报修任务
    function createNewTask(num){
        mui.openWindow({
            url:"{{asset('task'+String(num)+'html')}}",
            show:{
                autoShow:true,//页面loaded事件发生后自动显示，默认为true
                aniShow:'slide-in-right',//页面显示动画，默认为”slide-in-right“；
                duration:100,//页面动画持续时间，Android平台默认100毫秒，iOS平台默认200毫秒；
                event:'titleUpdate',//页面显示时机，默认为titleUpdate事件时显示
                extras:{}//窗口动画是否使用图片加速
            },
            waiting:{
                autoShow:true,//自动显示等待框，默认为true
                title:'正在加载...',//等待对话框上显示的提示内容
            }
        });
    }
</script>
</html>