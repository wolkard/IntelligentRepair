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
	        	<div class="mui-card-header" style="color: #FFFFFF;background-color: rgba(254, 201, 16, 1);">已完成任务</div>
				<ul id="renwu" class="mui-table-view">
					<!--任务模板-->
					<div id="allInformation"></div>
					<!--任务模板-->
				</ul>
			</div>
			
			</div>

</body>
<script>
//	需要ajax的地方：
//	1.打开这个页面的时候需要返回所有已完成任务的详细信息。包括图片url	
$(document).ready(function(){
	showTaskList();
});
function showTaskList(){
	var userId=document.cookie.substring(0,document.cookie.length).split("workerUserId=")[1].split(";")[0];
	$.ajax({
		url:'{{asset('/findWorkerTask')}}',
		async:false,
		type:"POST",
		dataType:'json',
		data:{state:[2],workerId:userId},
		success:function(data){
			console.log(data);
			var allInformation='';
			var allValue=['large_area_name','part_area_name','building','floor','room','item','description','created_at'];//所有要显示在页面上的值的key
			for(var i=0 ; i<data['data'].length ; i+=1){
				allInformation+='<li class="mui-table-view-cell" >\
										<a href="javascript:showDetails('+data['data'][i]['id']+');">';
				for(var k =0; k<allValue.length; k+=1){
					allInformation+=' <span>'+ data['data'][i][allValue[k]] +'</span>';
				}
				allInformation+='<span class="mui-badge mui-badge-warning mui-pull-right">已结束</span></a></li>';
			}

			$('#allInformation').html(allInformation);
		},
		error: function () {
			console.log("showTaskListError");
		}
	})
}
	//打开子页面查看任务具体信息
	function showDetails(taskId){
		document.cookie="workerTaskId="+taskId;//设置cookie用于页面传值
		mui.openWindow({
		    url:"{{asset('/receiveTaskView')}}",
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