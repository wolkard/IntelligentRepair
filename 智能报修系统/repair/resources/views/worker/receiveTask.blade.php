<!doctype html>
<html>

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<title></title>
		<script src="{{asset('js/jquery.js') }}" ></script>
		<script src="{{asset('js/jquery-3.2.1.min.js') }}"></script>
		<script src="{{asset('js/ajaxfileupload.js') }}" ></script>
		<script src="{{asset('js/mui.min.js') }}"></script>
		<link href="{{asset('css/mui.min.css') }}" rel="stylesheet">
		<script type="text/javascript">
			mui.init()
		</script>
	</head>

	<body>

	    <header class="mui-bar mui-bar-nav" style="background-color: rgba(254, 201, 16, 1);">
	      <a class="mui-icon mui-icon-left-nav mui-pull-left mui-action-back" style="color: #FFFFFF;"></a>
	      <h1 class="mui-title" style="color: #FFFFFF;font-size: 22.5px;"><b>智能报修</b></h1>
	    </header>
	    <!-- 主页面内容容器 -->
	    <div class="mui-content">
			
	        <div class="mui-card">
	        	<div class="mui-card-header" style="color: #FFFFFF;background-color: rgba(254, 201, 16, 1);">任务信息</div>
				<ul id="renwu" class="mui-table-view">
					<!--任务模板-->

					<li class="mui-table-view-cell" >
						<form class="mui-input-group" style="background-color: rgba(255, 255, 255, 0);">
						    <div id="allInformation"></div>

								<div id="getTaskButton1" class="mui-button-row" >
									<button id="accept" onClick="changeTaskState(1,'#accept');" type="button" class="mui-btn mui-btn-yellow" style="width:200px;background-color: rgba(254, 201, 16, 1);" >接取任务</button>
								</div>
								<div id="getTaskButton2" class="mui-button-row" >
								</div>
						</form>
					</li>
					<!--任务模板-->
				</ul>
			</div>
			
			</div>

</body>
<script>
//	需要ajax的地方：
//	1.点击接取任务后要发送工人的ID和任务的ID。返回1/0表示处理结果

	var allName=['大区域','系','建筑名称','楼层','房间号','物品','详细型号','报修说明','报修时间'];//所有提示信息
	var allValue=['large_area_name','part_area_name','building','floor','room','item','description','details','created_at'];//所有后台传过来的值的key
	var taskId=document.cookie.substring(0,document.cookie.length).split("workerTaskId=")[1].split(";")[0];//从cookie获取workerTaskId
	$(document).ready(function(){
		showTaskInformation();
	});

	//工人查找任务详细信息
	function showTaskInformation(){
		$.ajax({
			url:'{{asset('/findTaskAllInformation')}}',
			async:false,
			type:"POST",
			dataType:"json",
			data:{taskId:taskId},
			success:function(data){
				console.log(data);
					if(data['result']==1){
						var information ='';//将构建的html代码存入此变量
						for(var k =0 ;k<allValue.length;k+=1){//循环，构建html代码
							var content = data['data'][allValue[k]] ;//通过allValue取得的后台的值如：教学区、桌子等等
                            //任务详细信息
							information += '<div class="mui-input-row">\
											<label>'+allName[k]+'</label>\
											<input id= '+allValue[k]+'  type="text" class="mui-input-clear" value="'+content+'" readonly="readonly">\
										</div>';
						}
						if(data['data']['state']==1){//添加提交任务和提交延时任务相关按钮
							var finishTask='<button id="finish" onClick="changeTaskState(2,this.id);" type="button" class="mui-btn mui-btn-yellow" data-loading-text="提交中" style="position:static; width:200px;background-color: rgba(254, 201, 16, 1);" >提交任务</button>';
							var finishTaskTwo=		'<button id="giveup" type="button" onclick="changeTaskState(3,this.id)" class="mui-btn mui-btn-danger" style=" position:static;width:200px;" >无法完成</button>';
							$('#getTaskButton1').html(finishTask);
							$('#getTaskButton2').html(finishTaskTwo);
						}else if(data['data']['state']==2){//添加任务完成相关按钮
							var finishTask='<span  class="mui-btn mui-btn-green"  style="width:200px;background-color: green;" >该任务已完成</span>';
							$('#getTaskButton1').html(finishTask);
						}else if(data['data']['state']==3){//添加任务已经延时相关按钮
							var finishTask='<span  class="mui-btn mui-btn-yellow"  style="width:200px;background-color: #aa4a24;" >该任务已延时</span>';
							$('#getTaskButton1').html(finishTask);
						}
						$('#allInformation').html(information);//将构建的代码植入相应div中
					}else{//如果任务不存在，页面显示
						$('#allInformation').html('该任务已经不存在');
						$('#getTaskButton1').html('');
					}


			},
			error: function (){
				console.log('getTaskInformationError');
			}
		})
	}
//事件函数：当有关改变任务状态的按钮被点击
	function changeTaskState(state,theId){
		var userId=document.cookie.substring(0,document.cookie.length).split("workerUserId=")[1].split(";")[0];//从cookie获取workerUserId
		mui("#"+theId).button('loading');
    	mui.ajax('{{asset('/taskWorkerChange')}}',{
			data:{
				taskId:taskId,
				state:state,
				workerId:userId
			},
			dataType:'json',//服务器返回json格式数据
			type:'post',//HTTP请求类型
			timeout:10000,//超时时间设置为10秒；
			//headers:{'Content-Type':'application/json'},	              
			success:function(data){
				openWorkerHome();
			},
			error:function(xhr,type,errorThrown){
				//异常处理；
				alert(errorThrown);
				mui("#InfoPost").button('reset');
				//plus.nativeUI.toast('请求超时或异常');
				mui("#accept").button('reset'); 

			}
		});
		
	}
//打开子页面查看任务具体信息
function openWorkerHome(){
	mui.openWindow({
		url:"https://open.weixin.qq.com/connect/oauth2/authorize?appid=wwca50ec780fa208a2&redirect_uri=http%3a%2f%2f219.218.160.81%2frepair%2fpublic%2fconfirmWorkerTwo&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect",
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