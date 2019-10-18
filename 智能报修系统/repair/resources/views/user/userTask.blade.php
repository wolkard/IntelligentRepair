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
				<div class="mui-card-header" style="color: #FFFFFF;background-color: rgba(254, 201, 16, 1);">用户信息</div>
				<div id="userInformation" class="mui-card-content">

				</div>
			</div>

	        <div class="mui-card">
	        	<div class="mui-card-header" style="color: #FFFFFF;background-color: rgba(254, 201, 16, 1);">任务列表</div>
				<ul id="renwu" class="mui-table-view">
					<!--任务模板-->

					<!--任务模板-->
				</ul>
			</div>
			
			</div>

</body>
<script>
//	需要ajax的地方：	
//	1.用户信息可以使用页面传值所以无需再次请求
//  2.打开此页面时需要发送ajax请求得到任务列表，把任务列表整理一下返回，包括每个任务的具体信息和图片url。
	$(document).ready(function(){
		getInformation();
	});

	function getInformation(){
		$.ajax({
			url:"{{asset('/findUserTask')}}",
			async:false,
			type:'POST',
			dateType:'json',
			data:{userId:1},//此处需要修改！！！！！！！！！！！！！！！！！！！！！！！此处应为当前用户id
			success:function(data){
				var allTask='';//所有任务信息
				var userInformation='';//所有用户信息
				var allValue=['large_area_name','part_area_name','building','floor','room','item','state'];//所有要显示在页面上的值的key
				for(var i = 0 ; i < data['data'].length; i += 1){
					var theId=data['data'][i]['id'];//获得任务id
					var task='<li class="mui-table-view-cell" >\
						<a href="javascript:showDetails('+theId+');">';//点击后将值传入其中
					for (var k=0;k<allValue.length;k+=1) {
						if (allValue[k] == 'state') {
							var state = '';
							if (data['data'][i][allValue[k]] == 0) {
								state = '未进行';
							} else if (data['data'][i][allValue[k]] == 1) {
								state = '进行中...';
							} else if (data['data'][i][allValue[k]] == 2) {
								state = '完成';
							} else if (data['data'][i][allValue[k]] == 3) {
								state = '任务延迟';
							}
						}else if(allValue[k] == 'id'){
							task += '<span id="id">' + data['data'][i][allValue[k]] + '</span>';
						} else {
							task += '<span>' + data['data'][i][allValue[k]] + '</span>';
						}
					}
					task+='<span class="mui-badge mui-badge-warning mui-pull-right">'+state+'</span>';
					allTask+=task+'</a> </li> ';
			}
				var number = data['data'][0]['number'];
				var telephone = data['data'][0]['telephone'];
				userInformation += '<div  class="mui-card-content-inner">\
                                       <span style="">学号：'+number+'</span></br>\
								        <span style="">电话：'+telephone+'</span>\
                                   </div>';
				$('#renwu').html(allTask);
				$('#userInformation').html(userInformation);
			},
			error:function(data){
				console.log('error');
			}
		})
	}

	//打开子页面查看任务具体信息
	function showDetails(taskId){
        document.cookie="taskId="+taskId;//设置taskId的cookie，便于下个页面使用
        mui.openWindow({
            url:"{{asset('/userChangeReportView')}}",
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