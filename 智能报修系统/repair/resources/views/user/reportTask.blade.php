<!doctype html>
<html>

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<title></title>
		<script src="js/jquery.js" ></script>
	    <script src="js/jquery-3.2.1.min.js"></script>
	    <script src="js/ajaxfileupload.js"></script>
	    <script src="js/mui.min.js"></script>
	    <link href="css/mui.min.css" rel="stylesheet"/>
		<script type="text/javascript">
			mui.init()
		</script>
	</head>

	<body>
	<!-- 侧滑导航根容器 -->
	<!--<div class="mui-off-canvas-wrap mui-draggable mui-scalable">
	  
	  <div class="mui-inner-wrap">
	 
	    <aside class="mui-off-canvas-left">
	      <div class="mui-scroll-wrapper">
	        <div class="mui-scroll">
	          
	          ...
	        </div>
	      </div>
	    </aside>-->
	    <!-- 主页面标题 -->
	    <header class="mui-bar mui-bar-nav" style="background-color: rgba(254, 201, 16, 1);">
	      <a class="mui-icon mui-icon-left-nav mui-pull-left mui-action-back" style="color: #FFFFFF;"></a>
	      <h1 class="mui-title" style="color: #FFFFFF;font-size: 22.5px;"><b>智能报修</b></h1>
	    </header>
	    <!-- 主页面内容容器 -->
	    <div class="mui-content mui-scroll-wrapper">
	      <div class="mui-scroll">
	        <!-- 主界面具体展示内容 -->
	        <div class="mui-card" >
				<div class="mui-card-header" style="color: #FFFFFF;background-color: rgba(254, 201, 16, 1);">用户信息</div>
				<div id="userInformation" class="mui-card-content">
					<div class="mui-card-content-inner">
						<span style="">ID:</span>
					</div>
				</div>
			</div>
			
	       	<div class="mui-card">
				<div class="mui-card-content">
					<div class="mui-card-content-inner">
						<b>使用说明：</b>选择图片即可自动识别拍摄照片的物品以及地理位置，确认无误后提交即可。
					</div>
				</div>
			</div>
	        <div class="mui-card">
				<div class="mui-card-content">
					<div class="mui-card-content-inner">
    				
    				<form class="mui-input-group" style="background-color: rgba(255, 255, 255, 0);">
    					 <div class="mui-input-row mui-checkbox">
					        <label>自动填写</label>
					        <input type="checkbox" checked >
					     </div>
					    <div id="allInformation"></div>
					    <div class="mui-button-row" >
					       <!-- <button onclick="getImage()" type="button" class="mui-btn" style="width:200px;background-color: rgba(255, 255, 255, 1);" >拍照</button>-->
					    </div>
					    <div class="mui-button-row" >
					    	<button class="mui-btn" type="button" style="width:200px;" onclick="document.getElementById('imageup').click();">选择图片</button>
					    	<input id="imageup" style="opacity: 0;" type="file" accept="image/*" capture="camera">
					    </div>
					    <div class="mui-button-row" >
					        <button id="wifipost" type="button" class="mui-btn" data-loading-text="提交中" style="width:200px;background-color: rgba(255, 255, 255, 1);" >提交</button>
					    </div>
					</form>
					
    			</div>
				</div>
			</div>
			
	      </div>
	    </div>  
</body>
<script>

	$(document).ready(function(){
		getInformation();
		addform();//将表单输出到网页上；
		recognition();//图片识别
		findLocation();//位置识别
		getWifi();

	});
	//获得用户信息并输出到网页上
	function getInformation(){
		$.ajax({
			url:"{{asset('/findUserInformation')}}",
			async:false,
			type:'POST',
			dateType:'json',
			data:{openid:'openid1'},//此处需要修改！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！
			success:function(data){
				var number = data['data']['number'];
				var telephone = data['data']['telephone'];
				var userInformation = '<span style="">学号：'+number+'</span></br>\
								<span style="">电话：'+telephone+'</span>';
				$('#userInformation').html(userInformation);

			},
			error:function(data){
				console.log('error');
			}
		})
	}


//	//函数功能：获取周围所有WiFi信息
//	function getMac() {
//		if (plus.os.name == "Android") {  
//	          //WifiManager  
//	        var Context = plus.android.importClass("android.content.Context");  
//	        var WifiManager = plus.android.importClass("android.net.wifi.WifiManager");  
//	        var wifiManager = plus.android.runtimeMainActivity().getSystemService(Context.WIFI_SERVICE);  
//	        mac_intensity=wifiManager.getScanResults();
//	        return mac_intensity;  
//		}
//	}
	
	//函数功能：调用手机摄像头拍照并上传
//	function getImage(){
//		var strFolder="/storage/emulated/0/DCIM/getImage/Camera/";
//		var File = plus.android.importClass("java.io.File");
//	    var fd = new File(strFolder);
//	    if(!fd.exists()){ 
//	        fd.mkdirs();
//	        plus.nativeUI.toast("已创建目录");
//	    }
//		var cmr = plus.camera.getCamera();
//		var res = cmr.supportedImageResolutions[0];
//		var fmt = cmr.supportedImageFormats[0];
//		console.log("Resolution: "+res+", Format: "+fmt);
//		cmr.captureImage( function( path ){
//			//上传图片到服务器……
//			
//		},
//		function( error ) {
//			plus.nativeUI.toast("调用失败");
//		},
//		{filename:strFolder,resolution:res,format:fmt}
//	);
//	}

/*————————————————————————————————————————————————————————————————————————————————*/
/*————————————————————————————————————————————————————————————————————————————————*/
/*————————————————————————————————————————————————————————————————————————————————*/

	//当选择了一个图片时马上上传服务器判断
	$("#imageup").change(function(){
		//这个ajax是我找来的，还没实际测试过
		$.ajaxFileUpload({
            url: '/upload.aspx', //用于文件上传的服务器端请求地址
            secureuri: false, //是否需要安全协议，一般设置为false
            fileElementId: 'imageup', //文件上传域的ID
            dataType: 'json', //返回值类型 一般设置为json
            success: function (data){
            	//成功处理
            	//比如图片识别好了之后再把无线信息传上去判断一下？
            	//似乎表单什么的都没有了。。我也不造怎么去改
            },
            error: function (data){
            	//错误处理
            }
        });
	});
	
	//获取无线信息
	//获取需要两秒时间	获取的无线信息列表变量叫 info
	function getWifi(){
		      	if (plus.os.name == "Android"  && !wifiScaning) {
      			//WifiManager  
				var Context = plus.android.importClass("android.content.Context");  
				var WifiManager = plus.android.importClass("android.net.wifi.WifiManager");  
				var wifiManager = plus.android.runtimeMainActivity().getSystemService(Context.WIFI_SERVICE);
				wifiScaning=true;
		        wifiManager.startScan();//开始扫描
		        mui.toast('正在扫描…',{ duration:2000, type:'div' });
		        //延迟两秒返回结果
				setTimeout(function(){
					SSIDs=wifiManager.getScanResults();
					wifiScaning=false;
					//文本处理
			      	var info = HandleWifiInfo(SSIDs.toString());
			      	//  info 就是处理好的文本信息了，接下来是上传还是其他用都可以直接调用Info
			      	var Wifilength = info.length;
	     		 	//对于一些渣渣手机的信息长度是否等于0的判断
			      	if(Wifilength>0){
			      		mui.toast('获取成功',{ duration:'short', type:'div' });
//			      		//ajax
//			      		$.ajax({
//							url:'http://219.218.160.81/repair/public/addPosition',
//							type:'GET',
//							async:false,
//							data:{
//								znnz:info,//张楠女装
//								large_area_name:large_area_name,
//								part_area_name:part_area_name,
//								building:building,
//								floor:floor,
//								room:room
//							},
//							dataType:'jsonp',
//							jsonp:'callback',
//							timeout:10000,
//							//jsonpCallback:'result',
//							success:function(result){
//								mui.toast('上传成功',{ duration:'short', type:'div' });
//							},
//							error:function(msg){
//								mui.toast('上传发生错误',{ duration:'short', type:'div' });
//							}
//						});
					}else{
						mui.toast('未获取到信息，请检查WiFi',{ duration:'short', type:'div' });
					}
				},2000);
		        
	  	}	
	}
	//处理无线信息  String to Array
	function HandleWifiInfo(info){
		info = info.substring(1,info.length-1);
		infos = new Array();
		infos = info.split(",");
		wifi = new Array();
		wifis = new Array();
		for(i=0;i<infos.length;i++){
			item = infos[i].split("SSID: ");
			if(item[0]==" B"){
				wifi.push(item[1]);	
			}
			item = infos[i].split("evel: ");
			if(item[0] ==" l"){
				wifi.push(item[1]);
				wifis.push(wifi);
				wifi = new Array();
			}
		}
		//console.log(wifis);
		return wifis;
	}
	
/*————————————————————————————————————————————————————————————————————————————————*/
/*————————————————————————————————————————————————————————————————————————————————*/
/*————————————————————————————————————————————————————————————————————————————————*/

	//上传无线信息
	function findLocation(){
		var mac='[{"mac":"0a:69:6c:76:fc:bb","level":"-45"},{"mac":"06:69:6c:76:fc:ba","level":"-47"},{"mac":"06:69:6c:76:fc:a6","level":"-51"},{"mac":"0a:69:6c:76:fc:a7","level":"-58"},{"mac":"0a:69:6c:77:09:cf","level":"-69"},{"mac":"06:69:6c:77:00:9e","level":"-72"}]';
		$.ajax({
							url:'{{asset('location')}}',
							type:'POST',
							async:false,
							data:{
								mac:mac
							},
							success:function(result){
								console.log(result);
								mui.toast('上传成功',{ duration:'short', type:'div' });
							},
							error:function(msg){
								mui.toast('上传发生错误',{ duration:'short', type:'div' });
							}
						});
	}

	//图片识别
	function recognition(){
		$.ajax({
			url:'{{asset("/recognition")}}',
			dataType:"json",
			async:false,
			type:"POST",
			success:function(data){
				console.log(data);
				var allExample=['生活区','男生宿舍','2号楼','3','10'];
				for(var i=0;i<data['data'].length;i+=1){
					if(i==0){//等做出来其他情况，去掉此判断即可
						allExample.push(data['data'][i]['information']['item']);
						allExample.push(data['data'][i]['information']['description']);
					}
				}
				allExample.push('这坏了~ (‾◡◝)');
				var allId = ['large_area_name','part_area_name','building','floor','room','item','description','details'];
				for(var i=0;i<allId.length;i+=1){
					$('#'+allId[i]).val(allExample[i]);
				}
			},
			error:function(data){
				console.log(data);
			}
		})
	}

	//将报修表单输出到网页上
	function addform(){
		var allId = ['large_area_name','part_area_name','building','floor','room','item','description','details'];
		var allName = ['大区域','小区域','建筑名称','楼层','房间号','物品名称','物品描述','损坏描述'];
		/*var allExample = ['生活区','男生宿舍','2号楼','3','10','窗帘','蓝色','ee'];*/
		var allHtml='';
		for(var i = 0 ; i<allId.length ; i+=1 ){
			allHtml += '<div class="mui-input-row">\
							<label>'+allName[i]+'</label>\
							<input id="'+allId[i]+'" type="text" class="mui-input-clear" value="" >\
						</div>';
		}
		$('#allInformation').html(allHtml);
	}


	//事件函数：当提交按钮被点击，提交表单
	$("#wifipost").click(function(){
		//maclevel=SSIDs.toString();//获取无限信息，暂时注销
		mui(this).button('loading');
    	mui.ajax('{{asset('/addTask')}}',{
			data:{
				//mac_intensity:maclevel,//无限信息，暂时注销
				userId:'1',
				large_area_name:$("#large_area_name").val(),
				part_area_name:$("#part_area_name").val(),
				building:$("#building").val(),
				floor:$("#floor").val(),
				room:$("#room").val(),
				item:$('#item').val(),
				description:$('#description').val(),
				details:$('#details').val()
			},
			dataType:'json',//服务器返回json格式数据
			type:'post',//HTTP请求类型
			timeout:10000,//超时时间设置为10秒；
			//headers:{'Content-Type':'application/json'},	              
			success:function(data){
				if(data['result']==1){
					//plus.nativeUI.toast('上传成功');
					console.log('报修成功');
					mui("#wifipost").button('reset');
				}else if(data['result']==2){
					console.log('未找到物品');
				}else{
					console.log(data['remind']);
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
</script>	
	

</html>