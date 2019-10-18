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
		<link href="{{asset('css/bootstrap.min.css') }}" rel="stylesheet">
		<script type="text/javascript">
			mui.init()
		</script>
	</head>
	<body>
	    <header class="mui-bar mui-bar-nav" style="background-color: rgba(254, 201, 16, 1);">
	      <a href="index.html" class="mui-icon mui-icon-left-nav mui-pull-left" style="color: #FFFFFF;"></a>
	      <h1 class="mui-title" style="color: #FFFFFF;font-size: 22.5px;"><b>智能报修登陆</b></h1>
	    </header>
	    <!-- 主页面内容容器 -->
	    <div class="mui-content">
	        <div class="mui-card">
				<div class="mui-card-header" style="color: #FFFFFF;background-color: rgba(254, 201, 16, 1);">请输入电话号码：</div>
				<div class="mui-card-content">
					<div class="mui-card-content-inner">
						<input onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')" id="telephone" type="text" />
						<input  type="button" onclick="finish()"  class="mui-btn" style="margin-left:100px;width:100px;background-color: #eea236; color:white" value="确定">
					</div>
				</div>
			</div>
		</div>
	</body>
<script>
	function finish(){
		var telephone = $('#telephone').val();
		if(telephone.length>11){
			alert("电话号码最多为11位");
		}else{
			var openId="{{$openId}}";
			$.ajax({
				url:'{{asset('/telephone')}}',
				async:false,
				type:"POST",
				dataType:'json',
				data:{telephone:telephone,openId:openId},
				success:function(data){
					alert(data['remind']);
				},
				error:function(error){
					alert('请求错误');
				}
			})
		}

	}
</script>	
	

</html>