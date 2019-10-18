@extends('layouts.list')
@section('list')
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>报修系统管理端</title>
		<link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet"/>
        <link href="{{asset('css/admin/adminHomeV1.css')}}" rel="stylesheet"/>
	</head>
{{--    @section('content')--}}

	<body>
        <!--侧边栏-->
        <!--内容区-->
       @if(Auth::id()==2||Auth::id()==1||Auth::id()==3){{--如果是超级管理员则显示下面内容--}}
       <div  class="smallBody">
           <div class="title">
               <div class="titleWord">List | 管理员列表</div>
               <div class="titleLine"></div>
               <div class="panel-heading" style="float:right;margin-top:-30px;">
                   <div class="addAdmin" id="addAdmin" data-toggle="modal" data-target="#addAdminModal" >＋</div>
                   <button  class="btn btn-default" id="edit" href="#">删除管理员</button>
                   <button class="btn btn-default" id="allcheck" href="#" style="display:none;">全选&nbsp;</button>
                   <button class="btn btn-default" id="delete" href="#" style="display:none;" data-toggle="modal" data-target=".bs-example-modal-sm">删除&nbsp;</button>
                   <button class="btn btn-default" id="reset" href="#" style="display:none;">取消&nbsp;</button>
               </div>
           </div>
           <div id="Adminlist" class='bigAdminList'>
               {{--显示管理员列表--}}
               <div id="column1" class="column"></div>
               <div id="column2"   class="column"></div>
               <div id="column3"  class="column"></div>
               <div id="column4"  class="column"></div>
               <div style="width:800px;height:100px;float:left;"></div>
           </div>


       </div>

       {{--添加管理员--}}
       <div class="modal fade" id="addAdminModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
            <div class="panel panel-default addAdminMian" style="background:#2ab27b;">
                <div class="panel-heading">
                    添加管理员
                </div>
                <div class="panel-body"  style="width:200px;">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <div class="imageInformation">
                                <image src="{{asset('images/admin/email.jpg')}}" />
                            </div>
                            <input  id="inputEmail" type="email" class="form-control choose addAdminInput" style="background:#ffffff !important;" />
                        </div>
                        <div class="form-group">
                            <div class="imageInformation"><image src="{{asset('images/admin/phone.jpg')}}" /></div>
                            <input id="inputPhoneNumber"  type="text"  class="form-control choose  addAdminInput"  >
                        </div>
                        <div class="form-group">
                            <div  class="imageInformation"> 新的密码:</div>
                            <input type="text"  class="form-control choose addAdminInput" id="inputPsw" style="background:#ffffff !important;color: #000;">
                        </div>
                    </div>

                    <button type="button"  id="addAdminButton" class="btn btn-default changeInformationButton addAdminButton" style="margin-left:80px;" >添加管理员</button>
                </div>
            </div>
        </div>

        @else{{--不是超级管理员则显示如下内容--}}
       <div  class="smallBody">
           <div class="title">
               <div class="titleWord">Mine | 个人信息</div>
               <div class="titleLine"></div>
           </div>
           <div style="position:absolute;top:20%;left:35%;" id="addAdminInformation">
               <div class="panel panel-default" style="width: 400px;">
                   <div class="panel-heading" style="background-color: #2ab27b;color:white;">
                       修改密码
                   </div>
                   <div class="panel-body"  style="width:300px;margin-left:30px;">
                       <form class="form-horizontal">
                           <div class="form-group">
                               <label for="inputPhoneNumber" class="col-sm-2 " style="width:150px;">原密码:</label>
                               <div class="col-sm-10">
                                   <input type="text"  style="width:300px;" class="form-control" id="oldPassword" placeholder="原密码">
                               </div>
                           </div>
                           <div class="form-group">
                               <label for="inputPhoneNumber" class="col-sm-2" style="width:150px;">新密码:</label>
                               <div class="col-sm-10">
                                   <input type="text" style="width:300px;" class="form-control" id="newPassword" placeholder="新的密码">
                               </div>
                           </div>
                           <div class="form-group">
                               <label for="inputPhoneNumber" class="col-sm-2" style="width:150px;">重复新密码:</label>
                               <div class="col-sm-10">
                                   <input type="text" style="width:300px;" class="form-control" id="newPasswordAgain" placeholder="重复输入一次新的密码">
                               </div>
                           </div>
                       </form>

                       <button type="button"  style="background-color: #2ab27b;width:300px;" onclick="changePassword();" class="btn btn-primary btn-block" >修改密码</button>
                   </div>
               </div>
           </div>

        @endif

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
        </div>
		<!--模态框-->
	</body>

    <script src="{{asset('js/jquery-3.2.1.min.js')}}"></script>
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <script>


		$(document).ready(function(){
			//可加入auth判断是不是前三个管理员
            @if(Auth::id()==2||Auth::id()==1||Auth::id()==3)//如果是超级管理员则使用下面函数
			    getAdminList();//获取所有管理员列表
            @endif
            edit();//编辑删除管理员相关
            addAdmin();//添加管理员相关
		});

        //当修改信息按钮被点击
		function changeInformation(changeId){
			var adminId=changeId.split("_")[1];//命名方式为XXX_id，所以用此方法获取到管理员id
            //重新构建各个id
			var telephoneId="telephone_"+adminId;
			var passwordId="password_"+adminId;
			var remindId="remind_"+adminId;
			var saveId="save_"+adminId;
			var cancelId="cancel_"+adminId;
			$("#"+telephoneId).removeAttr("readonly");//将电话号码输入框变为可编辑
            $("#"+telephoneId).attr("style","background:#ffffff !important");//将电话号码输入框颜色变为白色
            $("#"+telephoneId).css("color","#000000");
			$("#"+remindId).show();//显示新密码提示
			$("#"+passwordId).show();//显示新密码输入框
			$("#"+saveId).show();//显示保存按钮
			$("#"+cancelId).show();//显示取消按钮
			$("#"+changeId).hide();//隐藏修改信息按钮
            $("#addAdminInformation").hide();//隐藏添加管理员模块
		}
        //当保存或者取消按钮被点击
        function ok(theId,judge){
            var adminId=theId.split("_")[1];//命名方式为XXX_id，所以用此方法获取到管理员id
            //重组后获得各部分内容的id
            var telephoneId="telephone_"+adminId;
            var passwordId="password_"+adminId;
            var remindId="remind_"+adminId;
            var saveId="save_"+adminId;
            var cancelId="cancel_"+adminId;
            var changeId="button_"+adminId;
            var saveNumber = $("[id^='save_']");//获取保存按钮的display，用来判断是否全部的按钮被隐藏
            $("#"+telephoneId).attr("readonly","readonly");//将电话号码输入框 变为只读
            $("#"+telephoneId).attr("style","background:#2ab27b !important");
            $("#"+telephoneId).css("color","#ffffff");
            $("#"+remindId).hide();//隐藏新密码提醒
            $("#"+passwordId).hide();//隐藏新密码输入框
            $("#"+saveId).hide();//隐藏保存按钮
            $("#"+cancelId).hide();//隐藏取消按钮
            $("#"+changeId).show();//显示修改信息按钮

            //循环检测是否所有的
            for(var i=0;i<saveNumber.length;i+=1){
               if($("#"+saveNumber[i]["id"]).is(":hidden")==false){
                   break;
               }
            }
            if(i==saveNumber.length){
                $("#addAdminInformation").show();
            }
            if(judge==1){//保存
                sendInformation(adminId);
            }
        }
        //发送修改的数据
        function sendInformation(adminId) {
            var telephoneId = "telephone_" + adminId;
            var emailId = "email_" + adminId;
            var passwordId = "password_" + adminId;
            var telephone = $("#" + telephoneId).val();
            var email = $("#" + emailId).val();
            var password = $("#" + passwordId).val();
            if (password == '') {
                password = null;
            }
            $.ajax({
                url: '{{asset('/changeAdminInformation')}}',
                type: 'POST',
                async: false,
                dataType: 'json',
                data:{telephone:telephone,email:email,password:password},
                success: function (data) {
                    console.log(data);
                },
                error: function () {
                    console.log("sendInformationError");
                }
            });
        }
            //添加管理员
        function addAdmin(){
            $("#addAdminButton").click(function(){
                var emailJudge= /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
                var password=$("#inputPsw").val();
                var telephone=$("#inputPhoneNumber").val();
                var email=$("#inputEmail").val();
                var reEmail=emailJudge.test(email);
                if(email==''){
                    alert('请输入邮箱');
                }else if(telephone==''){
                    alert('请输入电话号码')
                }else if(password==''){
                    alert('请输入密码')
                }else{

                    if(!reEmail) {
                        alert('邮箱格式不对');
                    }else if(telephone.length<11){
                        alert('电话号码至少11位');
                    }else if(isNaN(telephone) ){
                        alert('电话号码格式不对');
                    }else if((password.length)<6){
                        alert('密码至少6位');
                    }else{
                        $.ajax({
                            url:'{{asset('/addadmin')}}',
                            type:'POST',
                            async:false,
                            data:{password:password,telephone:telephone,email:email},//发的都是String
                            dataType:'json',
                            success:function(data){
                                console.log(data);
                                if(data['result']==1){
                                    alert("添加成功");
                                    location.href='{{asset('/admin/home')}}';
                                }else if(data['result']==0){
                                    alert("添加失败");
                                }else if(data['result']==2){
                                    alert("该邮箱已经被注册");
                                }
                            },
                            error:function(){
                                alert("请求出错");
                            }
                        });
                    }

                }

            });
        }

		
		//获取其他管理员信息
		function getAdminList(){
			$.ajax({
					url:'{{asset('/findadmin')}}',
					type:'POST',
					async:false,
					dataType:'json',
					success:function(data){
						if(data['result']==1){
							//添加信息到信息列表
							Info ='';
                            column1='';
                            column2='';
                            column3='';
                            column4='';

							for(var i=0 ; i<data['data'].length ;i+=1){//将每个管理员信息依次提出来
                                    Info='<div class="changeMarginTop">\
                                            <div id="allAdminInformation"  class="aAdmin" style="margin-top: 15px;">\
                                                <div class="oneAdminTop">\
                                                    <img style="margin-left:15%;margin-top:10%;height:80%;width:70%;" src="../images/head/'+data['data'][i]['head']+'">\
                                                </div>\
                                                <div class="oneAdminBottom">\
                                                   <div class="form-group">\
                                                        <div class="imageInformation"> <image src="{{asset('images/admin/email.jpg')}}" /></div>\
                                                        <input type="email" class="form-control choose input"  id="email_'+data['data'][i]['id']+'"  readonly="readonly"  value="'+data["data"][i]["email"]+'">\
                                                    </div>\
                                                    <div class="form-group">\
                                                        <div class="imageInformation"><image src="{{asset('images/admin/phone.jpg')}}" /></div>\
                                                        <input type="text"  class="form-control choose input" id="telephone_'+data["data"][i]["id"]+'" readonly="readonly" value="'+data["data"][i]["telephone"]+'">\
                                                    </div> \
                                                    <div class="form-group">\
                                                        <div id="remind_'+data['data'][i]["id"]+'" class="imageInformation"  style="display: none;"> 新的密码:</div>\
                                                        <input type="text"  class="form-control choose input" id="password_'+data['data'][i]['id']+'" style="display: none; background:#ffffff !important;color: #000;: ;" placeholder="不输入则不修改">\
                                                    </div>\
								                </div>\
								                <button type="button" id="button_'+data["data"][i]["id"]+'" onclick="changeInformation(this.id);" class="btn btn-default changeInformationButton">修改信息</button>\
                                                <button type="button" id="save_'+data["data"][i]["id"]+'" onclick="ok(this.id,1);" style="display: none;" class="btn btn-default changeInformationButton2">保存</button>\
                                                <button type="button" id="cancel_'+data["data"][i]["id"]+'" onclick="ok(this.id,0);" style="display: none;"  class="btn btn-default changeInformationButton2">取消</button>\
                                            </div>';

											Info+='<input type="checkbox" id="admincheck_'+data['data'][i]['id']+'" style="left:-70px; height:20px;width:50px; display: none;" class="checkbox-inline pull-right" />\</div>';
                                if((i+1)%4==0){
                                    column4 +=Info;
                                }else if((i+1)%4==3){
                                    column3 += Info;
                                }else if((i+1)%4==2){
                                    column2 +=Info;
                                }else{
                                    column1 +=Info;
                                }
							}
							$("#column1").html(column1);
                            $("#column2").html(column2);
                            $("#column3").html(column3);
                            $("#column4").html(column4);
						}else{
							alert("获取管理员列表失败");
						}
					},
					error:function(){
						console.log("请求出错");
					}
			});
		}
		
		//修改管理员密码
		function changePassword(){
			var oldPassword=$("#oldPassword").val();
            var newPassword=$("#newPassword").val();
            var newPasswordAgain=$("#newPasswordAgain").val();
            if(newPassword!=newPasswordAgain){
                alert('两次输入的密码不一致');
            }else{
                $.ajax({
                    url:'{{asset('/changepassword')}}',
                    type:'POST',
                    async:false,
                    data:{newPassword:newPassword,oldPassword:oldPassword,newPasswordAgain:newPasswordAgain,email:"{{Auth::user()['email']}}"},
                    dataType:'json',
                    success:function(data){
                        if(data['result']==1){
                            alert("修改成功");
                        }else if(data['result']==2){
                            alert("原密码错误");
                        }else if(data['result']==3){
                            alert("两次输入的密码不一致");
                        }else if(data['result']==0){
                            alert("修改失败");
                        }

                    },
                    error:function(){
                        alert("请求出错");
                    }
                });
            }

		}

        //编辑删除管理员相关
        function edit(){
            //函数功能：当编辑按钮按下时激活批量删除管理员的复选框
            $("#edit").click(function(){
                $("#edit").hide();
                $("#delete").show();
                $("#allcheck").show();
                $("#reset").show();
                $(".checkbox-inline").show();

            });
            //函数功能：当取消按钮按下时将管理员列表重置
            $("#reset").click(function(){
                $("#edit").show();
                $("#delete").hide();
                $("#allcheck").hide();
                $("#reset").hide();
                $(".checkbox-inline").hide();
                var allBox=$("[id^='admincheck_']");
                for (var i=0;i<allBox.length; i+=1){
                    $("#"+allBox[i].id).prop("checked", false);
                }
            });

            //函数功能：当全选按钮按下时选中所有的复选框
            //全局变量:[boolan] bechecked
            $("#allcheck").click(function(){
                var allBox=$("[id^='admincheck_']");
                for (var i=0;i<allBox.length; i+=1){
                    $("#"+allBox[i].id).prop("checked", true);
                }
            });

            //函数功能：当删除按钮按下时：统计CheckBox并提交数据
            $("#realdelete").click(function(){
                var deletelist=new Array();
                var allBox=$("[id^='admincheck_']");
                for (var i=0;i<allBox.length; i+=1){
                    if($("#"+allBox[i].id).is(':checked')){  //神奇的判断选中的方法
                        var adminId=allBox[i].id.split("_")[1];
                        deletelist.push(adminId);//添加进数组
                        $("#realdelete").button('loading');
                    }
                }
                if(deletelist.length!=0){
                    $.ajax({
                        url:'{{asset('/deladmin')}}',
                        type:'POST',
                        async:false,
                        data:{id:deletelist},
                        dataType:'json',
                        success:function(data){
                            if(data['result']==1){
                                location.href="{{asset('/admin/home')}}";
                            }else{
                                alert("删除失败");
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

        }

	</script>
</html>
@endsection
