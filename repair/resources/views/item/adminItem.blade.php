<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>报修系统管理端</title>
		<script src="{{asset('js/jquery-3.2.1.min.js')}}"></script>
		<script src="{{asset('js/bootstrap.min.js')}}"></script>
		<link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet"/>
	</head>
	<body>
		<!--侧边栏-->
		<div class="navbar-left" style="position:absolute;top:20%;width:20%;">
		<div class="container" style="width: 100%;">
		    <div class="row">
		        <div class="span2">
		            <ul class="nav nav-pills nav-stacked">
		                <li><a href="/admin/home">管理员</a></li>
		                <li><a href="/admin/worker">工人列表</a></li>
		                <li><a href="/admin/task">任务列表</a></li>
		                <li class="active" ><a href="/admin/items">物品资产</a></li>
		                <li><a href="#">数据统计</a></li> 
		            </ul>
		        </div>
		    </div>
		</div>
        </div>
        <!--侧边栏-->
        <!--内容区-->
        <div style="position:absolute;top:10%;left: 25%;">
        	<div class="panel panel-default" style="width: 100px;">
        		<div class="panel-heading">
			    	类别检索
			 	</div>
	        	<div class="list-group">
	        	  <a href="#" id="searchAllclass" class="list-group-item active">全部</a>
	        	  <a href="#" id="searchAttribute" class="list-group-item" data-toggle="modal" data-target=".bs-example-modal-lg">属性<span class="glyphicon glyphicon-chevron-right pull-right"></span></a>
	        	  <a href="#" id="searchLarge" class="list-group-item" data-toggle="modal" data-target=".bs-example-modal-lg">大类<span class="glyphicon glyphicon-chevron-right pull-right"></span></a>
	        	  <a href="#" id="searchSmall" class="list-group-item" data-toggle="modal" data-target=".bs-example-modal-lg">小类<span class="glyphicon glyphicon-chevron-right pull-right"></span></a>
	        	</div>	
			</div>	
        </div>
        
        <div style="position:absolute;top:10%;left: 35%;">
        	<div class="panel panel-default" style="width: 100px;">
        		<div class="panel-heading">
			    	区域检索
			 	</div>
	        	<div class="list-group">
	        	  <a href="#" id="searchAllarea" class="list-group-item active">全部</a>
	        	  <a href="#" id="large_area" class="list-group-item" data-toggle="modal" data-target=".bs-example-modal-lg">大区域<span class="glyphicon glyphicon-chevron-right pull-right"></span></a>
	        	  <a href="#" id="part_area" class="list-group-item" data-toggle="modal" data-target=".bs-example-modal-lg">小区域<span class="glyphicon glyphicon-chevron-right pull-right"></span></a>

			
				  
<!--  <a href="#" id="searchTime" class="list-group-item">时间<span class="glyphicon glyphicon-chevron-right pull-right"></a>-->	        	
	        	</div>	
			</div>	
        </div>
        
         <div style="position:absolute;top:10%;left:45%;">
        	
        	<div id="selectTimePanel" class="panel panel-default" style="width: 200px;">
			  <div class="panel-heading">
			    <h3 class="panel-title">时间检索</h3>
			  </div>
			  <div class="panel-body">
			  	<p id="wrongtime" class="text-danger" style="display: none;">请输入一个有效的时间范围</p>
			    <input type="date" id="beforetime" class="form-control" placeholder="Text input">
			 	<div style="height: 50px;line-height: 50px;">至</div>
			 	<input type="date" id="aftertime" class="form-control" placeholder="Text input">
			    </br><button id="searchTime" class="btn btn-primary btn-block">开始检索</button>
	
			  </div>
			</div>
			
        </div>
			
        </div>
        
        <div style="position:absolute;top:50%;left:25%;">
        		<div class="panel panel-default" style="width: 600px;">
	        		<div class="panel-heading">
				    	新增物品
				 	</div>
				 	<div class="panel-body">
						<form class="form-horizontal">

						  <div class="form-group">
						    <label for="inputAttribute" class="col-sm-2 control-label">属性</label>
						    <div class="col-sm-10">
						      <input type="text" class="form-control" id="inputAttribute" placeholder="水、电、建筑等">
						    </div>
						  </div>
						  <div class="form-group">
						    <label for="inputLarge" class="col-sm-2 control-label">大类</label>
						    <div class="col-sm-10">
						      <input type="text" class="form-control" id="inputLarge" placeholder="用水设备、谁管材料、通电材料、墙体等">
						    </div>
						  </div>
						  <div class="form-group">
						    <label for="inputSmall" class="col-sm-2 control-label">小类</label>
						    <div class="col-sm-10">
						      <input type="text" class="form-control" id="inputSmall" placeholder="水池、饮水机、消防栓、电线等">
						    </div>
						  </div>
						  <div class="form-group">
						    <label for="inputItem" class="col-sm-2 control-label">物品</label>
						    <div class="col-sm-10">
						      <input type="text" class="form-control" id="inputItem" placeholder="物品名">
						    </div>
						  </div>
						   <div class="form-group">
						    <label for="inputDescription" class="col-sm-2 control-label">详细描述</label>
						    <div class="col-sm-10">
						      <input type="text" class="form-control" id="inputDescription" placeholder="型号、颜色等">
						    </div>
						  </div>
						</form>
						<button type="button" id="additem" class="btn btn-primary btn-block">添加物品</button>
					</div>
				</div>
        </div>
       
        <div style="position:absolute;top:10%;left:66%;">
        		<div class="panel panel-default" style="width: 500px;">
        		<div class="panel-heading">
			    	物品列表
			 	</div>
			 		<!--物品列表模板-->
				    <ul id="items" class="list-group pre-scrollable">
					  <li class="list-group-item">
					  	<div class="media-body">
						    <h4 class="media-heading">物品名称 <small>属性 大类 小类 大区域 小区域</small></h4>
						    (description)
						</div>
					  </li>
					  <li class="list-group-item">
					  	<div class="media-body">
						    <h4 class="media-heading">物品名称 <small>属性 大类 小类 大区域 小区域</small></h4>
						    (description)
						</div>
					  </li>
					</ul>
					<!--物品列表模板-->
			 	</div>
        </div>
        
        <!-- 
        	这是一个模态框
        	检索的时候所有的可检索字段都返回在这里
        -->
		<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
		  <div class="modal-dialog modal-lg" role="document">
		    <div class="modal-content">
		    	
		    	<div style="height: 20px;"></div>
				<div id="searchItem" class="row">
					<div id="a1" class="col-sm-2 text-center"><a href="#" class="bg-info">教学区</a></div>
		    		<!--
                    	检索的字段
                    -->
		    	</div>
		    	
				<div style="height: 10px;"></div>
		     
		    </div>	
		  </div>
		</div>
		
        <!--暂时用不到的-->        
        <!--模态框-->
        <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
		   <div class="modal-dialog modal-sm" role="document">
		     <div class="modal-content">
		        <div class="modal-header">
		       		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		       		<h4 class="modal-title">确认删除</h4>
		        </div>
		        <div class="modal-body">
		        	<p>删除管理员是一个重要的决策，请确认您的操作</p>
		        </div>
		        <div class="modal-footer">
		        	<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
		        	<button type="button" class="btn btn-primary" id="realdelete">是，我要删除</button>
		        </div>
		    </div>
		  </div>
		</div>
		<!--模态框-->
	</body>
	<script>
		//用全局变量记录当前检索的id
		var attribute_id = '';			//属性
		var large_category_id = '';		//大类
		var small_category_id = '';		//小类
		var large_area_id = '';			//大区域
		var part_area_id = '';			//小区域
		var created_at_beforetime = ''; //创建时间（前）
		var created_at_aftertime = '';  //创建时间（后）
		
		//检索被点击之后更改模态框里的内容
		//这里只给出了"属性"被点击的动作，其他的改完直接复制即可
		$("#searchAttribute").click(function(){
			$("#searchItem").html('');
//			$.ajax({
//					url:'茂茂的.php',
//					type:'POST',
//					async:false,
//					//data:{large_category_id:large_category_id},
//					dataType:'json',
//					success:function(searchHtml){
//						$("#searchItem").html(searchHtml);
//					},
//					error:function(){
//						alert("请求出错");
//					}
//			});
			//html字符串的样例，判断检索字段的id如果和发送的一致，那就给那个字段加个 bg-info 类表示选中状态。
			searchHtml='<div id="a1" onclick=search("a1") class="col-sm-2 text-cente		r"><a href="#" class="bg-info">属性1</a></div> <div id="a2" onclick=search("a2") class="col-sm-2 text-center "><a href="#" >属性2</a></div> ';
			$("#searchItem").html(searchHtml);
		});
		
		//检索函数：
		//当任意一个检索字段被点击
		//同时发送 attribute_id, large_category_id, small_category_id, item_id
		//并返回相应结果铺在列表里
//		
		function search(id){
			//判断点击的检索字段，每个检索字段html元素的ID都由 a,l,s,i + 数字组成， 如属性的id="a1" 物品的id="i2" 等等
			//根据id前缀判断id数字属于哪一级，记录数据并检索。
			//若发送的是空字符串，则检索该级全部物品
			//若有和数据库结构不符的地方请改- -
			switch (id[0]){
				case 'a':
					attribute_id=id[1];		break;
				case 'l':
					large_category_id=id[1];	break;
				case 's':
					small_category_id=id[1];	break;
				case 'i':
					item_id=id[1];	break;
				default:
					break;
			}
			$.ajax({
					url:'茂茂的.php',
					type:'POST',
					async:false,
					data:{
						attribute_id:attribute_id,
						large_category_id:large_category_id,
						small_category_id:small_category_id,
						item_id:item_id
					},
					dataType:'json',
					success:function(searchHtml){
						//后台整理好的HTML字符串直接铺在列表里
						$("#items").html(searchHtml);
						//该字段显示为选中状态
						$(id).addClass('bg-info');
					},
					error:function(){
						alert("请求出错");
					}
			});			
			
		}
		
		//时间检索函数
		//获取前后时间，并执行search()
		$("#searchTime").click(function(){
			created_at_beforetime = $("#beforetime").val();
			created_at_aftertime = $("#aftertime").val();
			if((created_at_beforetime!='' && created_at_aftertime!='')&&(created_at_beforetime<=created_at_aftertime)){
				$("#wrongtime").hide();
				created_at_beforetime += ' 00:00:00';
				created_at_aftertime +=  ' 23:59:59';
				console.log('beforetime:'+created_at_beforetime);
				console.log('aftertime:'+created_at_aftertime);
				
			}else{
				$("#wrongtime").show();
			}
			
		});
		
		function searchtime(){
			
			$.ajax({
					url:'大山的.php',
					type:'POST',
					async:false,
					data:{
						state:state,
						large_area_id:large_area_id,
						part_area_id:part_area_id,
						created_at_beforetime:created_at_beforetime,
						created_at_aftertime:created_at_aftertime
					},
					dataType:'json',
					success:function(searchHtml){
						//后台整理好的HTML字符串直接铺在列表里
						$("#items").html(searchHtml);
						//该字段显示为选中状态
					},
					error:function(){
						alert("请求出错");
					}
			});			
			
		}
		
		//  函数功能：添加物品信息
		//  基本都是复制过来的 = =。辛苦你改改这里了
		$("#additem").click(function(){
			attribute_name=$("#inputAttribute").val();
			large_category_name=$("#inputLarge").val();
			small_category_name=$("#inputSmall").val();
			$.ajax({
					url:'茂茂的.php',
					type:'POST',
					async:false,
					//'name','age','sex','type','area','telephone'
					data:{
						attribute_name:attribute_name,
						large_category_name:large_category_name,
						small_category_name:small_category_name
					},
					dataType:'json',
					success:function(result){
						if(result['result']==1){
							alert(result['remind']);// “添加成功”
						}else{
							alert("添加失败");
						}
						//$("#realdelete").button('reset');
					},
					error:function(){
						//$("#realdelete").button('reset');
						alert("请求出错");
					}
			});
		});
	</script>
</html>
