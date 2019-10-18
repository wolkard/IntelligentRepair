@extends('layouts.list')
@section('list')
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>报修系统管理端</title>
		<script src="{{asset('js/jquery-3.2.1.min.js')}}"></script>
		<script src="{{asset('js/bootstrap.min.js')}}"></script>
		<script src="{{asset('js/echarts.min.js')}}"></script>
		<link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet"/>
		<link href="{{asset('css/admin/adminTaskV5.css')}}" rel="stylesheet"/>
	</head>
	<body>
        <!--内容区-->
		<div class="smallBody">
			<div class="taskListWord">List | 任务列表</div>
			<div class="taskListWordOtherLine"></div>
			<div class="item">
				<div class="panel panel-default" style="width: auto;">
					<div class="panel-heading" style="background: #465b83;color:white;">
						物品检索
					</div>
					<div class="list-group">
					  <a href="#" id="searchAllItem"  onclick="searchAll('item');" class="list-group-item ">全部</a>
						<div id="allItemSearchName"></div>
						<!--
							用于显示刚进入页面时的检索
						-->
					  </div>
				</div>
			</div>
			<div class="position">
				<div class="panel panel-default" style="width: auto;">
					<div class="panel-heading" style="background: #465b83;color:white;">
						位置检索
					</div>
					<div class="list-group">
						<a href="#" id="searchAllArea" onclick="searchAll('area');" class="list-group-item" >全部</a>
						<div id="allAreaSearchName">
							<!--
								用于显示刚进入页面时的检索
							-->
						</div>
					</div>
				</div>
			</div>
			<div class="time">

				<div id="selectTimePanel" class="panel panel-default" >
				  <div class="panel-heading">
					<h3 class="panel-title">时间检索</h3>
				  </div>
				  <div class="panel-body">
					<p id="wrongtime" class="text-danger" style="display: none;">请输入一个有效的时间范围</p>
					<input type="date" id="beforetime" class="form-control" placeholder="Text input">
					<div style="height: 50px;line-height: 50px;">至</div>
					<input type="date" id="aftertime" class="form-control" placeholder="Text input">
					</br><button id="searchTime"  class="btn btn-primary btn-block" style="background: #465b83;">开始检索</button>

				  </div>
				</div>

			</div>

			<div class="taskList" style="border:1px solid #465b83;" >
						<ul id="items" class="list-group pre-scrollable" style="border-bottom:1px solid #465b83;min-height:60%;min-width:650px;">
							<!--
								显示任务列表
							-->
						</ul>
						<div id="itemsInformation"  style="min-height:40%;min-width:650px;">
							<!--显示任务详细信息-->
							<div class="itemInfomrations">
								<img style="height:38px;width:22px;" src="../images/admin/position.jpg">
								位置:
								<span id="taskPosition" class="itemInformation">点击查看报修位置 </span>
							</div>
							<div class="itemInfomrations">
								<img style="height:30px;width:30px;" src="../images/admin/item.jpg">
								物品:
								<span id="taskItem" class="itemInformation">点击查看报修物品</span>
							</div>
							<div class="itemInfomrations" style="height:60%">
								<img style="height:30px;width:30px;" src="../images/admin/word.jpg">
								备注:
								<div id="taskWord" class="otherInformation">点击查看报修备注</div>
							</div>
						</div>
					</div>
			</div>

			<!-- Large modal -->
			<div id="largeItemModel">
				<!--
					用于显示物品检索
				-->
			</div>
			<div id="largeAreaModel">
				<!--
					用于显示位置检索
				-->
			</div>
			<div class="echarts">
				<div id="item" style="height:470px;width: 45%;float: left;"></div>
				<div id="area" style="height:470px;width: 45%;float: left;margin-left:10%;"></div>
			</div>
		</div>
	</body>
	<script>





/*所有全局变量*/
var created_at_beforetime = null; //创建时间（前）
var created_at_aftertime = null;  //创建时间（后）
var betweenTime =null;//时间段
var allSearch=new Object();//检索的json对象

//物品或者位置检索再细化，只需在此处添加id和name，然后请求成功后得到的数据向新的模态框输出检索。
var allItemId=['attribute','large_category','small_category'];//物品检索，刚进入显示在页面上的检索的id
var allItemName=['属性','大类','小类'];//物品检索，刚进入显示在页面上的检索名称分类
var allAreaId=['large_area','part_area'];//位置检索，刚进入显示在页面上的检索的id
var allAreaName=['大区域','小区域'];//位置检索，刚进入显示在页面上的检索名称分类

		$(document).ready(function(){
			showSearchModel();//将所有的模态框植入到html中
			getTaskList(null,null);//获得任务列表
			getSearchList();//获得检索列表
		});

		//点击全部后移除json对象中已有此类检索，然后发送请求重新获取任务数据
		function searchAll(name){
			//判断需要使用 哪些刚进入页面显示的检索列表内容
			if(name=='item'){
				var theId="allItemSearchName";
				var allId=allItemId;
				var allName=allItemName;
			}else if(name=='area'){
				var theId="allAreaSearchName";
				var allId=allAreaId;
				var allName=allAreaName;
			}

			for(var i=0;i<allId.length;i+=1){
				delete allSearch[allId[i]];//移除json对象中的全部相应检索
				var theHtml=allName[i]+'<span class="glyphicon glyphicon-chevron-right pull-right"></span>';
				$("#"+allId[i]+"__F").html(theHtml);//将初始化检索初始化
			}
			/*console.log(all);*/
			$(".bg-info").removeClass('bg-info');//添加选定效果
			console.log(allSearch);

			if(betweenTime!=null){
				if(betweenTime[0]==null){
					betweenTime=null;
				}
			}
			getTaskList(allSearch,betweenTime);
		}

		//检索函数：
		//当任意一个检索字段被点击
		//构建json对象形成{large_area:2,part_area：3}的样式
		function search(theId){
			/*实现点击只选中一个检索的效果*/
			$("#"+theId+"").prevAll().removeClass("bg-info");//移除同级前方的css样式
			$("#"+theId+"").nextAll().removeClass("bg-info");//移除后方同级的css样式
			$("#"+theId+"").addClass('bg-info');//添加选定效果

			/*将初始化检索列表替换为所选文字*/
			var modelId = $("#"+theId+"").parent().parent().parent().parent()[0].id;//获取模态框id
			var firstId = modelId.split("__")[0]+"__F";//重组得到初始化检索列表id
			var theName=$("#"+theId+"").children().html();//获取选择后的检索的文字
			var theHtml=theName+'<span class="glyphicon glyphicon-chevron-right pull-right"></span></a>';
			$("#"+firstId).html(theHtml);//将选择后的文字替换到最初的检索列表上

			/*构建检索对象*/
			var name=theId.split("-")[0];//获得名字，便于后台根据此名字查找对应表，构建字段
			var thisId=theId.split("-")[1];//获得id，便于根据id检索
			allSearch[name]=thisId;//构建对象

			if(betweenTime!=null){
				if(betweenTime[0]==null){
					betweenTime=null;
				}
			}
			getTaskList(allSearch,betweenTime);
		}

		//获得检索列表
		function getSearchList(){
			$.ajax({
				url:'{{asset('/taskSearchList')}}',
				async:false,
				type:"POST",
				dataType:'json',
				success:function(data){
					var attributeSearch='';
					//所有物品检索
					for(var i=0; i<data['data']['item'].length; i+=1){//循环获得每个属性
						var attributeId="attribute-"+data['data']['item'][i]['attribute_id'];
						var attributeName=data['data']['item'][i]['attribute_name'];
						attributeSearch += '<div id="'+attributeId+'" onclick="search(this.id);" class="col-sm-2 text-center "><a href="#" class="addClass" >'+attributeName+'</a></div>';
						var largeCategorySearch='';
						for(var k=0 ; k<data['data']['item'][i]['attribute_aontain'].length ; k+=1){//循环获得每个大类
							var largeCategoryId="large_category-"+data['data']['item'][i]['attribute_aontain'][k]['large_category_id'];//每个大类id
							var largeCategoryName=data['data']['item'][i]['attribute_aontain'][k]['large_category_name'];//每个大类名字
							largeCategorySearch+='<div id="'+largeCategoryId+'" onclick="search(this.id);" class="col-sm-2 text-center "><a href="#" class="addClass" >'+largeCategoryName+'</a></div>';
							var smallCategorySearch='';
							for(j=0 ; j<data['data']['item'][i]['attribute_aontain'][k]['large_category_aontain'].length ; j+=1){//循环获得每个小类
								var smallCategoryId = "small_category-"+data['data']['item'][i]['attribute_aontain'][k]['large_category_aontain'][j]['small_category_id'];//每个小类id
								var smallCategoryName =data['data']['item'][i]['attribute_aontain'][k]['large_category_aontain'][j]['small_category_name'];//每个小类名字
								smallCategorySearch+='<div id="'+smallCategoryId+'" onclick="search(this.id);" class="col-sm-2 text-center "><a href="#" class="addClass" >'+smallCategoryName+'</a></div>';
							}
							$('#small_category').append(smallCategorySearch+'</br></br>');
						}
						$('#large_category').append(largeCategorySearch+'</br></br>');
					}
					$('#attribute').html(attributeSearch+'</br></br>');
					//所有位置检索
					var largeAreaSearch='';
					for(var m=0; m<data['data']['position'].length; m+=1) {//循环获得每个属性
						var largeAreaId = "large_area-"+data['data']['position'][m]['large_area_id'];
						var largeAreaName = data['data']['position'][m]['large_area_name'];
						largeAreaSearch += '<div id="' + largeAreaId + '" onclick="search(this.id);" class="col-sm-2 text-center "><a href="#" class="addClass" >' + largeAreaName + '</a></div>';
						var partAreaSearch = '';
						for (var n = 0; n < data['data']['position'][m]['large_area_aontain'].length; n+= 1) {//循环获得每个大类
							var partAreaId = "part_area-"+data['data']['position'][m]['large_area_aontain'][n]['part_area_id'];//每个大类id
							var partAreaName = data['data']['position'][m]['large_area_aontain'][n]['part_area_name'];//每个大类名字
							partAreaSearch += '<div id="' + partAreaId + '" onclick="search(this.id);"  class="col-sm-2 text-center " style="width:auto;"><a href="#" class="addClass" >' + partAreaName + '</a></div>';
						}
						$('#part_area').append(partAreaSearch+"</br></br></br>");
					}
					$('#large_area').html(largeAreaSearch);
				},
				error:function(){
					console.log('error');
				}

			});
		}

		//获得任务列表
		function getTaskList(search,betweenTime){
			//任务列表
			$.ajax({
				url:'{{asset('/findTaskList')}}',
				async:false,
				type:"POST",
				dataType:'json',
				data:{search:search,betweenTime:betweenTime},
				success:function(data){
					console.log(data);
					var allTaskList='';//所有任务html代码
					//若添加或删除显示内容，修改下面的allValue或者后台的查询字段
					var allValue=['large_area_name','part_area_name','building','floor','room','item',/*'details','created_at',*/'state'];//全部的要显示的一个任务的所有id
					for(var i=0 ; i<data['data'].length ; i+=1){//循环，将所有任务分为条
						var task='<li onclick="findTaskAllInformation(this.id);" id="'+data['data'][i]['id']+'" class="list-group-item" style="cursor: pointer; background:#465b83;color:white;  border:0;">&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp ';
						for(var k =0 ; k<allValue.length ; k+=1){//循环添加每条任务的id和显示的值
							if(allValue[k]=='state'){//将state的数字替换为相应的文字
								var state = '';
								var theColor='';
								if(data['data'][i][allValue[k]]==0){
									state =  '未开始';
									theColor="color:red;!important"
								}else if(data['data'][i][allValue[k]]==1){
									state =  '正在进行...';
									theColor="color:yellow;!important"
								}else if(data['data'][i][allValue[k]]==2){
									state = '已完成';
									theColor="color:black !important"
								}else if(data['data'][i][allValue[k]]==3){
									state = '延时处理';
								}else{
									state = "未知任务状态"
								}
								task+='  <span style="float:right;'+theColor+'">'+state+'</span>';
							}else{
								task+='&nbsp <span>'+ data['data'][i][allValue[k]] +'</span>';
							}
						}
						allTaskList+=task;
					}
					$('#items').html(allTaskList);//输出到html中

					// 基于准备好的dom，初始化echarts实例
					var myChart = echarts.init(document.getElementById('item'));
					// 指定图表的配置项和数据
					var option = {
						baseOption: {
							timeline: {
								show:false
							},
							title: {
								subtext: '数据来自胡编乱造(●ˇ∀ˇ●)',
								subtextStyle: {
									align: 'center'
								}
							},
							tooltip: {},
							legend: {
								x: 'right',
								data: ['属性比例', '大类比例','小类比例'],
								selectedMode: 'single'
							},
							calculable: true,
							series: [{
								name: '属性比例',
								type: 'pie',
								center: ['50%', '50%']
								//radius: '28%'
							},{
								name: '大类比例',
								type: 'pie',
								center: ['50%', '50%']
								//radius: '28%'
							},{
								name: '小类比例',
								type: 'pie',
								center: ['50%', '50%']
								//radius: '28%'
							}]
						},
						options: [{
							title: {
								text: '任务的物品类别比例'
							},
							series: [
								{
									name: '属性比例',
									data: data['graphData']['attribute_name']
								}, {
									name: '大类比例',
									data: data['graphData']['large_category_name']
								}, {
									name: '小类比例',
									data: data['graphData']['small_category_name']
								}
							]
						}]
					};
					// 使用刚指定的配置项和数据显示图表。
					myChart.setOption(option);


					// 基于准备好的dom，初始化echarts实例
					var myChart = echarts.init(document.getElementById('area'));
					// 指定图表的配置项和数据
					var option = {
						baseOption: {
							timeline: {
								show:false
							},
							title: {
								subtext: '数据来自胡编乱造(●ˇ∀ˇ●)',
								subtextStyle: {
									align: 'center'
								}
							},
							tooltip: {},
							legend: {
								x: 'right',
								data: ['大区域比例', '小区域比例'],
								selectedMode: 'single'
							},
							calculable: true,
							series: [{
								name: '大区域比例',
								type: 'pie',
								center: ['50%', '50%']
								//radius: '28%'
							},{
								name: '小区域比例',
								type: 'pie',
								center: ['50%', '50%']
								//radius: '28%'
							}]
						},
						options: [{
							title: {
								text: '任务的区域分布'
							},
							series: [
								{
									name: '大区域比例',
									data: data['graphData']['large_area_name']
								}, {
									name: '小区域比例',
									data: data['graphData']['part_area_name']
								}
							]
						}]
					};

					// 使用刚指定的配置项和数据显示图表。
					myChart.setOption(option);


				},
				error:function(){
					console.log('error');
				}

			});

		}

		//查找任务详细信息
		function findTaskAllInformation (taskId){
			changeTaskListCss(taskId);
			$.ajax({
				url:'{{asset('/findTaskAllInformation')}}',
				async:false,
				type:"POST",
				dataType:'json',
				data:{taskId:taskId},
				success:function(data) {
					console.log(data);
					var information =data['data'];
					var taskPosition=information['large_area_name'] +' '+ information['part_area_name'] +' '+ information['floor']+information['room']+'房间';
					var taskItem =information['attribute_name']　+' '+ information['large_category_name']　+' '+ information['small_category_name'] +' '+ information['item'];
					var taskWord = information['details'];
					if(taskWord==null){
						taskWord='暂无备注';
					}
					$('#taskPosition').html(taskPosition);
					$('#taskItem').html(taskItem);
					$('#taskWord').html(taskWord);
				},
				error:function(){
					alert('查找任务详细信息请求错误')
				}
			})
		}

		oldTaskId=null;
		function changeTaskListCss(taskId){
			if(oldTaskId!=null){
				$('#'+oldTaskId).css('background','#465b83');
			}
			$('#'+taskId).css('background','dodgerblue');
			oldTaskId=taskId;
		}

		//将所有的模态框植入到html中
		function showSearchModel(){
			var modelItem='';//所有模态框
			var itemSearchName='';//所有检索分类
			var modelArea='';
			var areaSearchName='';
			for(var i=0 ; i<allItemId.length ; i+=1){
				//所有的物品检索弹出框
				//id用XXXX_M目的：点击某个检索后，获得此检索的模态框的id为XXXX_M,用split("_")分割重组后得到此初始化检索列表id,XXXX_F
				modelItem += '<div id="'+allItemId[i]+'__M" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">\
								<div  class="modal-dialog modal-lg" role="document">\
									<div class="modal-content">\
										<div style="height: 20px;"></div>\
										<div id="'+allItemId[i]+'" class="row">\
										</div>\
										<hr/>\
										<div class="row" id="searchRow" >\
										</div>\
										<div style="height: 10px;"></div>\
									</div>\
								</div>\
							</div>';
				//初始的物品检索
				//id用XXXX__F目的：点击某个检索后，获得此检索的模态框的id为XXXX__M,用split("_")分割重组后得到此初始化检索列表id,XXXX__F
				itemSearchName+='<a href="#" id="'+allItemId[i]+'__F"  class="list-group-item" data-toggle="modal" data-target="#'+allItemId[i]+'__M" >'+allItemName[i]+'<span class="glyphicon glyphicon-chevron-right pull-right"></span></a>';
			}
			for(k=0 ; k<allAreaId.length ; k+=1){
				//所有的位置检索弹出框
				//id用XXXX__M目的：点击某个检索后，获得此检索的模态框的id为XXXX__M,用split("_")分割重组后得到此初始化检索列表id,XXXX__F
				modelArea += '<div id="'+allAreaId[k]+'__M" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">\
								<div  class="modal-dialog modal-lg" role="document">\
									<div class="modal-content">\
										<div style="height: 20px;"></div>\
										<div id="'+allAreaId[k]+'" class="row">\
										</div>\
										<hr/>\
										<div class="row" id="searchRow" >\
										</div>\
										<div style="height: 10px;"></div>\
									</div>\
								</div>\
							</div>';
				//添加初始化位置检索列表
				//id用XXXX_F目的：点击某个检索后，获得此检索的模态框的id为XXXX_M,用split("_")分割重组后得到此初始化检索列表id,XXXX_F
				areaSearchName+='<a href="#" id="'+allAreaId[k]+'__F"  class="list-group-item" data-toggle="modal" data-target="#'+allAreaId[k]+'__M" >'+allAreaName[k]+'<span class="glyphicon glyphicon-chevron-right pull-right"></span></a>';
			}
			$('#allItemSearchName').html(itemSearchName);
			$('#largeItemModel').html(modelItem);
			$('#allAreaSearchName').html(areaSearchName);
			$('#largeAreaModel').html(modelArea);
		}
		//时间检索函数
		$("#searchTime").click(function(){
			created_at_beforetime = $("#beforetime").val();
			created_at_aftertime = $("#aftertime").val();
			if(created_at_beforetime=='' && created_at_aftertime==''){//判断时间选择是否为空，若为空，时间段改为null，查找全部时间段任务
				betweenTime=null;
				getTaskList(allSearch,betweenTime);//执行查找任务
				return true;
			}else if(created_at_beforetime<=created_at_aftertime){//判断时间段选择是否正确，
				$("#wrongtime").hide();
				created_at_beforetime += ' 00:00:00';
				created_at_aftertime +=  ' 23:59:59';
				console.log('beforetime:'+created_at_beforetime);
				console.log('aftertime:'+created_at_aftertime);
				betweenTime =[created_at_beforetime,created_at_aftertime];
				getTaskList(allSearch,betweenTime);//执行查找任务
				return true;
			}else{
				$("#wrongtime").show();
			}
		});
	</script>
</html>
@endsection