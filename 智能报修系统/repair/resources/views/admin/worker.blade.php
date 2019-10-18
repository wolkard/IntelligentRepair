@extends('layouts.list')
@section('list')
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>报修系统管理端</title>
		<script src="{{asset('js/jquery-3.2.1.min.js')}}"></script>
    	<script src="{{asset('js/bootstrap.min.js')}}"></script>
   		<link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet"/>
		<link href="{{asset('css/admin/workerV5.css')}}" rel="stylesheet"/>
	</head>
	<body>

        <!--内容区-->
		<div class="smallBody">
			<div class="workerListWord">List | 工人列表</div>
			<div class="workerListWordOtherLine"></div>
			<div class="panel-heading edit" style=" font-size: 20px; font-weight: 700;" >
				<a  id="edit" href="#" >删除工人</a>
				<a id="delete" href="#" style="display:none;" data-toggle="modal" data-target=".bs-example-modal-sm">删除&nbsp;</a>
				<a id="allcheck" href="#" style="display:none;">全选&nbsp;</a>
				<a  id="reset" href="#" style="display:none;">取消&nbsp;</a>
			</div>
			<div class="workerList">
				<div class="panel panel-default " >
					<ul  class="list-group pre-scrollable workerListTwo" id="Workerlist">
					</ul>
				</div>

			</div>

			<div class="addAndInformation">
					<div class="panel panel-default "  >
						<div class="panel-heading workerInformatiuonHead" style="color:white;background:#338e96;height:70px;">
							<span id="wordInformation">新增工人</span>
							<p id="word" style="margin-left:20%;color:#ddd;">显示、修改工人详细信息请点击左侧</p>
							<span id="addWorkerButton" onclick="addWorker()" style="display: none ; cursor:pointer; margin-left:200px;">新增工人</span>
						</div>
						<div class="panel-body addAndInformationTwo" >
							<form class="form-horizontal">
								<div id="workerID" style="display: none"></div>
							  <div class="form-group information" >
								<label for="inputName" class="col-sm-2 control-label">姓名*</label>
								<div class="col-sm-10">
								  <input type="text" class="form-control" id="inputName" placeholder="Name">
									<p id="name" style="display: none;" class="from-control-static text-danger"></p>
								</div>
							  </div>
							  <div class="form-group information" >
								<label for="inputPhoneNumber" class="col-sm-2 control-label">手机号*</label>
								<div class="col-sm-10">
								  <input type="text" class="form-control" id="inputPhoneNumber" placeholder="PhoneNumber">
									<p id="telephone" style="display: none;" class="from-control-static text-danger"></p>
								</div>
							  </div>
							  <div class="form-group information" >
								<label for="inputAge" class="col-sm-2 control-label">年龄</label>
								<div class="col-sm-10">
								  <input type="text" class="form-control" id="inputAge" placeholder="Age" style="width: 100px;">
									<p id="age" style="display: none;" class="from-control-static text-danger"></p>
								</div>
							  </div>
							  <div class="form-group information">
								<label for="inputSex" class="col-sm-2 control-label">性别</label>
								<div class="col-sm-10">
								  <select class="form-control" id="inputSex" style="width: 100px;" >
									  <option value ="">未知</option>
									  <option value ="男">男</option>
									  <option value ="女">女</option>
								  </select>
								</div>
							  </div>
							  <div class="form-group information">
								<label for="inputType" class="col-sm-2 control-label">类型*</label>
								<div class="col-sm-10">
								  <select class="form-control" id="inputType" style="width: 100px;">
									  <option value ="水工">水工</option>
									  <option value ="电工">电工</option>
								  </select>
								</div>
							  </div>
							  <div class="form-group information">
								<label for="inputArea" class="col-sm-2 control-label">所属区域*</label>
								<div class="col-sm-10">
								  <input type="text" class="form-control" id="inputArea" placeholder="1">
									<p id="area" style="display: none;" class="from-control-static text-danger"></p>
								</div>
							  </div>
							</form>
							<button type="button" id="editworkerInfo" class="btn btn-primary btn-block" style="display: none;background:#338e96;">确认修改</button>
							<button type="button" id="addworker" class="btn btn-primary btn-block" style="background:#338e96;">添加工人</button>
						</div>
					</div>
			</div>



			<!--模态框-->
			<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
			   <div class="modal-dialog modal-sm" role="document">
				 <div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">确认删除</h4>
					</div>
					<div class="modal-body">
						<p>删除工人是一个重要的决策，请确认您的操作</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
						<button type="button" class="btn btn-primary" id="realdelete">是，我要删除</button>
					</div>
				</div>
			  </div>
			</div>
			<!--模态框-->
		</div>
	</body>
	<script>
//	需要ajax的地方：
//  1.打开此页面时要返回工人列表
//	2.点击添加工人要发送表单里所有信息
//	3.点击确认修改要发送工人的ID和所有信息

		
		//初始化一些参数
		var bechecked=false;//管理员列表复选框参数
		var data = new Array();

		//函数功能：点击工人后将下图的工人信息变为修改模式
		function editworker(id){

			$("#wordInformation").text('工人信息');//将“添加工人”文字替换成“工人信息”
			$("#word").hide();
			$("#addWorkerButton").show();//显示添加工人按钮
			$("#editworkerInfo").show();//显示确认修改按钮
			$("#addworker").hide();//隐藏确认添加工人按钮
            $('#inputName').val(data[id]['name']);
            $('#inputPhoneNumber').val(data[id]['telephone']);
            $('#inputAge').val(data[id]['age']);
            $('#inputType').val(data[id]['type']);
            $('#inputSex').val(data[id]['sex']);
            $('#inputArea').val(data[id]['area']);
            $("#workerID").text(data[id]['id']);
		}
		//函数功能：新增工人
		function addWorker(){
			$("#wordInformation").text('新增工人');//将“工人信息”文字替换成“新增工人”
			$("#word").show();
			$("#addWorkerButton").hide();//隐藏添加工人按钮
			$("#addworker").show();//显示添加工人按钮
			$("#editworkerInfo").hide();//隐藏确认修改
			$('#inputName').val('');
			$('#inputPhoneNumber').val('');
			$('#inputAge').val('');
			$('#inputType').val('');
			$('#inputSex').val('');
			$('#inputArea').val('');
        }
		//函数功能：当删除工人按钮按下时激活批量删除管理员的复选框
		$("#edit").click(function(){
			$("#edit").hide();
			$("#delete").show();
			$("#allcheck").show();
			$("#reset").show();
			$(".sss").show();


		});
		//函数功能：当取消按钮按下时将管理员列表重置
		$("#reset").click(function(){
			$("#edit").show();
			$("#delete").hide();
			$("#allcheck").hide();
			$("#reset").hide();
            $(".sss").hide();
		});


		//函数功能：当全选按钮按下时选中所有的复选框
		//全局变量:[boolan] bechecked
		$("#allcheck").click(function(){
            $(data).each(function (i,worker) {
				if(!$("#wokercheck"+worker['id']).prop("checked")){
				    bechecked=false;
				}
			});
			if(bechecked==false){
                $(data).each(function (i,worker){
					$("#wokercheck"+worker['id']).prop("checked",true);
				});
				bechecked=true;
			}else{
                $(data).each(function (i,worker){
					$("#wokercheck"+worker['id']).prop("checked",false);
				});
				bechecked=false;
			}

		});
		
		//函数功能：当删除按钮按下时：统计CheckBox并提交数据
		$("#realdelete").click(function(){
			var deletelist=[];
            $(data).each(function (i,worker){
				if($("#wokercheck"+worker['id']).is(':checked')){
					deletelist.push(worker['id']);//添加进数组
					$("#realdelete").button('loading');
				}
			});
			if(deletelist.length!=0){
				$.ajax({
						url:'{{asset('/delectWorker')}}',
						type:'POST',
						async:false,
						data:{id:deletelist},
						dataType:'json',
						success:function(result){
							if(result['result']==1){
								alert(result['remind']);
                                location.reload();
							}else{
								alert(result['remind']);
							}
							$("#realdelete").button('reset');
						},
						error:function(){
							$("#realdelete").button('reset');
							alert("请求出错");
						}
					});
			}
			console.log(deletelist);//控制台显示选中的列表
			
			
		});

		//
		//  函数功能：获取工人列表
		function getUserInfo(){
			$.ajax({
				url:'{{asset('/findWorkInformation')}}',
				type:'POST',
				async:false,
				//data:{},
				dataType:'json',
				success:function(result){
					if(result['result']==1){
						//添加信息到信息列表
						data = result['data'];
						Info ='';
						console.log(result);
						$(result['data']).each(function (i,worker) {
							Info=Info+'<div class="worker" onclick="editworker('+i+');"><div class="img"><img style="height:100%;width:100%;" src="../images/head/'+worker['head']+'"></div><li class="list-group-item" style="position:relative;border:0;left:35%;margin-top:-100px;background:#338e96;width:65%;height:100px;">';
							Info+='<div id="admin'+worker['id']+'"><div id="woker'+worker['id']+'">'+worker['name']+'</div>';
							Info+='<div>电话：'+worker['telephone']+'</div>';
							Info+='<div>类型：'+worker['type']+'</div>';
							Info=Info+'<div id="wokerEdit'+worker['id']+'" class="glyphicon" href="javascript:editworker('+i+');"></div>';
							Info=Info+'<input type="checkbox" id="wokercheck'+worker['id']+'"  class="checkbox-inline pull-right sss" style="display: none;"/></li></div>';
						});
						$("#Workerlist").html(Info);
					}else{
						alert("获取工人列表失败");
					}
				},
				error:function(){
					alert("请求出错");
				}
			});
		}

		//修改工人
		//  函数功能：修改工人信息
		$("#editworkerInfo").click(function(){
		    var id =$("#workerID").text();
			var name=$("#inputName").val();
			var age=$("#inputAge").val();
			var sex=$("#inputSex").val();
			var type=$("#inputType").val();
			var area=$("#inputArea").val();
			var telephone=$("#inputPhoneNumber").val();
			$.ajax({
					url:'{{asset('/changeWorkInformation')}}',
					type:'POST',
					async:false,
					//'name','age','sex','type','area','telephone'
					data:{id:id,name:name,age:age,sex:sex,type:type,area:area,telephone:telephone},
					dataType:'json',
					success:function(result){
						if(result['result']==1){
							alert(result['remind']); //“修改成功”
                            location.reload();
						}else{
							alert("修改失败");
						}
						//$("#realdelete").button('reset');
					},
					error:function(error){
						if (error.status==422){
							var json=JSON.parse(error.responseText);
							if (json.name!=null){
								$('#name').show();
								$('#name').text(json.name);
							}
							if (json.age!=null){
								$('#age').show();
								$('#age').text(json.age);
							}
							if (json.telephone!=null){
								$('#telephone').show();
								$('#telephone').text(json.telephone);
							}
							if (json.area!=null){
								$('#area').show();
								$('#area').text(json.area);
							}
						}
						else {
							alert("请求失败");
						}
					}
			});
		});
		
		//  函数功能：添加工人信息
		$("#addworker").click(function(){
			var name=$("#inputName").val();
			var age=$("#inputAge").val();
			var sex=$("#inputSex").val();
			var type=$("#inputType").val();
			var area=$("#inputArea").val();
			var telephone=$("#inputPhoneNumber").val();
			$.ajax({
					url:'{{asset('/addWorker')}}',
					type:'post',
					async:false,
					//'name','age','sex','type','area','telephone'
					data:{name:name,age:age,sex:sex,type:type,area:area,telephone:telephone},
					dataType:'json',
					success:function(result){
						if(result['result']==1){
							alert(result['remind']);// “添加成功”
							location.reload();
						}else{
							alert("添加失败");
						}
						//$("#realdelete").button('reset');
					},
					error:function(error){
					    if (error.status==422){
                            var json=JSON.parse(error.responseText);
                            if (json.name!=null){
                                $('#name').show();
                                $('#name').text(json.name);
							}
                            if (json.age!=null){
                                $('#age').show();
                                $('#age').text(json.age);
							}
							if (json.telephone!=null){
                                $('#telephone').show();
                                $('#telephone').text(json.telephone);
							}
                            if (json.area!=null){
                                $('#area').show();
                                $('#area').text(json.area);
							}
                        }
						else {
                                alert("请求失败");
						}
					}
			});
		});

		window.onload=getUserInfo();

		
	</script>
</html>
@endsection