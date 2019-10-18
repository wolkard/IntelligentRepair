<!doctype html>
<html id="main">

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
	      <a href="index.html" class="mui-icon mui-icon-left-nav mui-pull-left" style="color: #FFFFFF;"></a>
	      <h1 class="mui-title" style="color: #FFFFFF;font-size: 22.5px;"><b>智能报修</b></h1>
	    </header>
	    <!-- 主页面内容容器 -->
	    <div class="mui-content">
	        <div class="mui-card">
				<div class="mui-card-header" style="color: #FFFFFF;background-color: rgba(254, 201, 16, 1);">工人信息</div>
				<div class="mui-card-content">
					<div class="mui-card-content-inner">
						<span style="">ID:</span>
					</div>
				</div>
			</div>
			
	        <div class="mui-card">
	        	<div class="mui-card-header" style="color: #FFFFFF;background-color: rgba(254, 201, 16, 1);">任务查询</div>
				<ul id="renwu" class="mui-table-view">
					<!--任务模板-->
					<li class="mui-table-view-cell" >
					<a href="javascript:showList('{{asset('seeReceiveTaskView')}}',null);">
						<span class="mui-pull-left">可接取任务</span>
						<span class="mui-icon mui-icon-arrowright mui-pull-right" style="font-size: 20px;"></span>
					</a>
					</li>
					<li class="mui-table-view-cell" >
					<a href="javascript:showList('{{asset('seeFinishTaskView')}}',null);">
						<span class="mui-pull-left">已完成任务</span>
						<span class="mui-icon mui-icon-arrowright mui-pull-right" style="font-size: 20px;"></span>
					</a>
					</li>
					<!--任务模板-->
					<li id="selfTaskList" class="mui-table-view-cell" >
						<span class="mui-pull-left">还没有接取任务</span>
						<span class="mui-icon  mui-pull-right" style="font-size:53px;"></span>
					</li>
				</ul>
			</div>

			</div>
</body>
<script>
	$(document).ready(function(){
		getWorkerId();//获取access_token
		showSelfTaskList();//显示工人自己信息列表（正在进行的和延迟的）
	});

	//获取工人id
	function getWorkerId(){
		$.ajax({
			url:'{{asset('/confirmWorkerOne')}}',
			async: false,
			type: "GET",
			error:function (){
				alert('获取工人id请求错误');
			}
		})
	}


	//显示工人自己信息列表（正在进行的和延迟的）
	function showSelfTaskList(){
		var workerId="{{$workerId['id']}}";
		document.cookie="workerUserId="+workerId;
		$.ajax({
			url:'{{asset('/findWorkerTask')}}',
			async:false,
			type:"POST",
			dataType:'json',
			data:{workerId:workerId,state:[1,3]},
			success:function(data){
				var allInformation='';//所有将要植入html的信息
				var allValue=['large_area_name','part_area_name','building','floor','room','item','description','created_at'];//所有要显示在页面上的值的key
				for(var i=0 ; i<data['data'].length ; i+=1){//循环将后台返回的数据形成单条
					allInformation+='<li class="mui-table-view-cell" >\
											<a href="javascript:showList(\'receiveTaskView\','+data['data'][i]['id']+');">';
					for(var k =0; k<allValue.length; k+=1){//循环将每条数据插入html语句
						allInformation+=' <span>'+ data['data'][i][allValue[k]] +'</span>';
					}
					var state='';
					if(data['data'][i]['state']==1){
						state='正在进行...';
					}else if(data['data'][i]['state']==3){
						state='任务延迟';
					}
					allInformation+='<span class="mui-badge mui-badge-warning mui-pull-right">'+state+'</span></a></li>';
				}
				$('#selfTaskList').html(allInformation);
			},
			error: function () {
				console.log("showTaskListError");
			}
		})
	}

	//打开子页面查看任务列表
	function showList(url,taskId){
		if(taskId!=null){
			document.cookie="workerTaskId="+taskId;//设置cookie用于页面传值
		}
		mui.openWindow({
		    url:url,
		    show:{
		      autoShow:true,//页面loaded事件发生后自动显示，默认为true
		      aniShow:'slide-in-right',//页面显示动画，默认为”slide-in-right“；
		      duration:100,//页面动画持续时间，Android平台默认100毫秒，iOS平台默认200毫秒；
		      event:'titleUpdate',//页面显示时机，默认为titleUpdate事件时显示
		      extras:{}//窗口动画是否使用图片加速
		    },
		    waiting:{
		      autoShow:true,//自动显示等待框，默认为true
		      title:'正在加载...'//等待对话框上显示的提示内容
		    }
		});
	}

</script>	
	

</html>