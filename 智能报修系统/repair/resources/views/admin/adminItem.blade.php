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
    <link href="{{asset('css/admin/adminItemV11.css')}}" rel="stylesheet"/>
</head>
<body>
<!--内容区-->
<div class="smallBody">
    <div class="itemListWord">List | 物品资产</div>
    <div class="itemListWordOtherLine"></div>
    <div class="itemSearch">
        <div class="panel panel-default" style="width: 100px;">
            <div class="panel-heading" style="background-color:#337ab7;color:#ffffff;">
                物品检索
            </div>
            <div class="list-group" >
                <a href="#" id="searchAllItem"  onclick="searchAll('item');" style="background-color:#fff;color:black;"  class="list-group-item active">全部</a>
                <div id="allItemSearchName"></div>
                <!--
                    用于显示刚进入页面时的检索
                -->
            </div>
        </div>
    </div>
    <div class="positionSearch">
        <div class="panel panel-default" style="width: 100px;">
            <div class="panel-heading" style="background-color:#337ab7;color:#ffffff;">
                位置检索
            </div>
            <div class="list-group">
                <a href="#" id="searchAllArea" onclick="searchAll('area');" style="background-color:#fff;color:black;" class="list-group-item active">全部</a>
                <div id="allAreaSearchName">
                    <!--
                        用于显示刚进入页面时的检索
                    -->
                </div>
            </div>
        </div>
    </div>
    <div class="timeSearch">
        <div id="selectTimePanel" class="panel panel-default" style="width: 200px;">
            <div class="panel-heading" style="background-color:#337ab7;color:#ffffff;">
                <h3 class="panel-title" >时间检索</h3>
            </div>
            <div class="panel-body">
                <p id="wrongtime" class="text-danger" style="display: none;">请输入一个有效的时间范围</p>
                <input type="date" id="beforetime" class="form-control" placeholder="Text input">
                <div style="height: 50px;line-height: 50px;">至</div>
                <input type="date" id="aftertime" class="form-control" placeholder="Text input">
                </br><button id="searchTime"  class="btn btn-primary btn-block">开始检索</button>
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
    {{--新增物品--}}
    <div class="addItem">
        <div class="panel panel-default addItemSize" >
            <div class="panel-heading" style="background-color:#337ab7;color:#ffffff;">
                <span id = "newItems">新增物品</span>
                <span id = "newItemsButton" style="display: none;margin-left:60% !important;color:yellow;cursor: pointer;" onclick="addItem();">新增物品</span>
                {{--<a class="pull-right" id="itemsReset" href="#" style="display:none;" onclick="itemsReset()">取消&nbsp;</a>--}}
            </div>
            <div class="panel-body" style="width: 100px;">
                <div style="float:left;width:350px">
                    <form class="form-horizontal" >

                        <div class="form-group">
                            <label for="inputAttribute" class="col-sm-2 control-label">属性</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="attribute_name" value="物品">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputLarge" class="col-sm-2 control-label">大类</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="large_category_name" value="电">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputSmall" class="col-sm-2 control-label">小类</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="small_category_name" value="小型电器">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputItem" class="col-sm-2 control-label">物品</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="item" value="插排">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputDescription" class="col-sm-2 control-label">详细描述</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="description" value="这是描述">
                            </div>
                        </div>
                        <div class="form-group" hidden>
                            <label for="inputDescription" class="col-sm-2 control-label">category_id</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="category_id" placeholder="" readonly>
                            </div>
                        </div>
                    </form>
                </div>
                <div style="float: left;width:350px;">
                    <form class="form-horizontal">

                        <div class="form-group">
                            <label for="inputLargearea" class="col-sm-2 control-label">大区域</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="large_area_name" value="生活区">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPartarea" class="col-sm-2 control-label">小区域</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="part_area_name" value="男生宿舍">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputBuilding" class="col-sm-2 control-label">建筑</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="building" value="2号楼">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputFloor" class="col-sm-2 control-label">楼层</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="floor" value="2">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputRoom" class="col-sm-2 control-label">房间号</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="room" value="10">
                            </div>
                        </div>
                        <div class="form-group" hidden>
                            <label for="inputDescription" class="col-sm-2 control-label">position_id</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="position_id" placeholder="" readonly>
                            </div>
                        </div>

                    </form>
                </div>
                <button type="button" id="additem" class="btn btn-primary btn-block" style="margin-left:50px;width:300px;">添加物品</button>
                <button type="button" id="updateitem" class="btn btn-primary btn-block" style="display:none;margin-left:50px;width:300px;" onclick="updateItems()">修改物品</button>
            </div>
        </div>
    </div>
    {{--物品列表--}}
    <div class="itemInformation">
        <div class="panel panel-default itemInformationSize" >
            <div class="panel-heading" style="background-color:#337ab7;color:#ffffff;">
                物品列表
                <span style="margin-left:70px;">点击 查看、修改 物品详细信息</span>
                <a class="pull-right" id="edit" href="#" onclick="edit()">编辑</a>
                <a class="pull-right" id="delete" href="#" style="display:none;" data-toggle="modal" data-target=".bs-example-modal-sm">删除&nbsp;</a>
                <a class="pull-right" id="allcheck" href="#" style="display:none;" onclick="allSelect()">全选&nbsp;</a>
                <a class="pull-right" id="reset" href="#" style="display:none;" onclick="reset()">取消&nbsp;</a>
            </div>
            <!--物品列表模板-->
            <ul id="items" class="list-group pre-scrollable itemListHeight" >

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
                    <!--
                        检索的字段
                    -->
                </div>

                <div style="height: 10px;"></div>

            </div>
        </div>
    </div>
    <!--删除模态框-->
    <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">确认删除</h4>
                </div>
                <div class="modal-body">
                    <p>确定要删除吗？</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-primary" id="realdelete" onclick="deletChecked()">是，我要删除</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!--删除模态框-->
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

        //函数功能：显示编辑菜单
        function edit() {
            $("#edit").hide();
            $("#delete").show();
            $("#allcheck").show();
            $("#reset").show();
            $(".checkbox-inline").show();
        }
        //函数功能：恢复编辑菜单
        function reset() {
            $("#edit").show();
            $("#delete").hide();
            $("#allcheck").hide();
            $("#reset").hide();
            $(".checkbox-inline").hide();
        }
        //函数功能：全选物品列表与取消全选
        function allSelect() {
            //选取所有复选框的id
            var allCategoryCheck = $("[id^='categorycheck_']");
            var flag = 0;
            for(var i=0; i<allCategoryCheck.length;i++)
            {
                if($("#"+allCategoryCheck[i].id).is(':checked'))
                {
                    flag++;
                }
            }

            if(flag==i){
                i = 0;
                for(i; i<allCategoryCheck.length;i++)
                {
                    $("#"+allCategoryCheck[i].id).prop("checked", false);
                }
            }else{
                for(var i=0; i<allCategoryCheck.length;i++)
                {
                    $("#"+allCategoryCheck[i].id).prop("checked", true);
                }
            }
        }
        //函数功能：删除选中
        function deletChecked() {

            var deleteIds = {};
			/*var category_id = new Array;
			 var position_id = new Array;*/
            //选取所有复选框的id
            var allCategoryCheck = $("[id^='categorycheck_']");
            var num=0;
            for(var i=0; i<allCategoryCheck.length;i++)
            {
                if($("#"+allCategoryCheck[i].id).is(':checked'))
                {
                    ids = allCategoryCheck[i].id.split('_');
                    var categoryId = ids[1];
                    //category_id.push(categoryId);
                    deleteIds['category_id['+num+']'] = categoryId;
                    var positionId = ids[2];
                    //position_id.push(positionId);
                    deleteIds['position_id['+num+']'] = positionId;
                    num+=1;
                }
            }
			/*deleteIds['category_id'] = category_id;
			 deleteIds['position_id'] = position_id;*/
            $.ajax({
                url:'{{asset('admin/delectItem')}}',
                type:'POST',
                async:false,
                data:deleteIds,
                //data:{large_category_id:large_category_id},
                dataType:'json',
                success:function(result){
                    if(result['result'])
                    {
                        alert(result['remind']);
                    }else{
                        alert(result['remind']);
                    }
                    location.reload();
                },
                error:function(error){
                    console.log(error);
                    alert("请求出错");
                    location.reload();
                }
            });

        }
        function itemsEdit(i) {
            $('#'+i).parent().nextAll().css('background-color',"rgb(51, 122, 183)");
            $('#'+i).parent().prevAll().css('background-color',"rgb(51, 122, 183)");
            $('#'+i).parent().css('background-color',"#465b83");
            $('#itemsReset').show();
            $('#additem').hide();
            $('#updateitem').show();
            $('#newItems').html('修改物品');
            $('#newItemsButton').show();//显示上面的黄色的添加物品
            $('#item').val($('#item_'+i+'').html());
            $('#attribute_name').val($('#attribute_name_'+i+'').html());
            $('#large_category_name').val($('#large_category_name_'+i+'').html());
            $('#large_area_name').val($('#large_area_name_'+i+'').html());
            $('#part_area_name').val($('#part_area_name_'+i+'').html());
            $('#building').val($('#building_'+i+'').html());
            $('#floor').val($('#floor_'+i+'').html());
            $('#room').val($('#room_'+i+'').html());
            $('#small_category_name').val($('#small_category_name_'+i+'').html());
            $('#description').val($('#description_'+i+'').html());
            $('#category_id').val($('#category_id_'+i+'').html());
            $('#position_id').val($('#position_id_'+i+'').html());
        }

        //点击上面的添加物品后，消除input中内容
        function addItem(){
            var allId=['attribute_name',"large_category_name","small_category_name","item","description","large_area_name","part_area_name","building","floor","room"]
            $.each(allId,function (index,value){
                $("#"+value).val("");
            })
            $('#additem').show();
            $('#updateitem').hide();
            $('#newItemsButton').hide();
            $('#newItems').html('添加物品');
        }
        function itemsReset() {
            $('#itemsReset').hide();
            $('#additem').show();
            $('#updateitem').hide();
            $('#newItems').html('添加物品');
            $('#item').val('');
            $('#attribute_name').val('');
            $('#large_category_name').val('');
            $('#large_area_name').val('');
            $('#part_area_name').val('');
            $('#building').val('');
            $('#floor').val('');
            $('#room').val('');
            $('#small_category_name').val('');
            $('#description').val('');
            $('#category_id').val('');
            $('#position_id').val('');
        }
        function updateItems()
        {
            var item = $('#item').val();
            var attribute_name = $('#attribute_name').val();
            var large_category_name = $('#large_category_name').val();
            var large_area_name = $('#large_area_name').val();
            var part_area_name = $('#part_area_name').val();
            var building = $('#building').val();
            var floor = $('#floor').val();
            var room = $('#room').val();
            var small_category_name = $('#small_category_name').val();
            var description = $('#description').val();
            var category_id = $('#category_id').val();
            var position_id = $('#position_id').val();
            $.ajax({
                url:'{{asset('/changeItemInformation')}}',
                async:false,
                type:"POST",
                dataType:'json',
                data:{
                        item:item,
                        attribute_name:attribute_name,
                        large_category_name:large_category_name,
                        large_area_name:large_area_name,
                        part_area_name:part_area_name,
                        building:building,
                        floor:floor,
                        room:room,
                        small_category_name:small_category_name,
                        description:description,
                        category_id:category_id,
                        position_id:position_id
                    },
                success:function(result){
                    if(result['result']){
                        alert(result['remind']);
                        location.reload();
                    }else{
                        alert(result['remind']);
                        location.reload();
                    }
                },
                error:function(){
                    console.log('error');
                }

            });

        }





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
            var theHtml=theName;
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
                url:'{{asset('/itemSearchList')}}',
                async:false,
                type:"POST",
                dataType:'json',
                success:function(data){
                    var attributeSearch='';
                    //所有物品检索
                    for(var i=0; i<data['data']['item'].length; i+=1){//循环获得每个属性
                        var attributeId="attribute-"+data['data']['item'][i]['attribute_id'];
                        var attributeName=data['data']['item'][i]['attribute_name'];
                        attributeSearch += '<div id="'+attributeId+'" onclick="search(this.id);" class="col-sm-2 text-center "><a href="#" class="addClass" data-dismiss="modal">'+attributeName+'</a></div>';
                        var largeCategorySearch='';
                        for(var k=0 ; k<data['data']['item'][i]['attribute_aontain'].length ; k+=1){//循环获得每个大类
                            var largeCategoryId="large_category-"+data['data']['item'][i]['attribute_aontain'][k]['large_category_id'];//每个大类id
                            var largeCategoryName=data['data']['item'][i]['attribute_aontain'][k]['large_category_name'];//每个大类名字
                            largeCategorySearch+='<div id="'+largeCategoryId+'" onclick="search(this.id);" class="col-sm-2 text-center "><a href="#" class="addClass"data-dismiss="modal" >'+largeCategoryName+'</a></div>';
                            var smallCategorySearch='';
                            for(j=0 ; j<data['data']['item'][i]['attribute_aontain'][k]['large_category_aontain'].length ; j+=1){//循环获得每个小类
                                var smallCategoryId = "small_category-"+data['data']['item'][i]['attribute_aontain'][k]['large_category_aontain'][j]['small_category_id'];//每个小类id
                                var smallCategoryName =data['data']['item'][i]['attribute_aontain'][k]['large_category_aontain'][j]['small_category_name'];//每个小类名字
                                smallCategorySearch+='<div id="'+smallCategoryId+'" onclick="search(this.id);" class="col-sm-2 text-center "><a href="#" data-dismiss="modal" class="addClass" >'+smallCategoryName+'</a></div>';
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
                        largeAreaSearch += '<div id="' + largeAreaId + '" onclick="search(this.id);" class="col-sm-2 text-center "><a href="#" class="addClass" data-dismiss="modal">' + largeAreaName + '</a></div>';
                        var partAreaSearch = '';
                        for (var n = 0; n < data['data']['position'][m]['large_area_aontain'].length; n+= 1) {//循环获得每个大类
                            var partAreaId = "part_area-"+data['data']['position'][m]['large_area_aontain'][n]['part_area_id'];//每个大类id
                            var partAreaName = data['data']['position'][m]['large_area_aontain'][n]['part_area_name'];//每个大类名字
                            partAreaSearch += '<div id="' + partAreaId + '" onclick="search(this.id);"  class="col-sm-2 text-center " style="width:auto;"><a href="#" data-dismiss="modal" class="addClass" >' + partAreaName + '</a></div>';
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

        //获得物品列表
        function getTaskList(search,betweenTime){
            //物品列表
            console.log(search,betweenTime);
            $.ajax({
                url:'{{asset('/findItemInformation')}}',
                async:false,
                type:"POST",
                dataType:'json',
                data:{search:search,betweenTime:betweenTime},
                success:function(data){
                    var allTaskList='';//所有任务html代码
                    //若添加或删除显示内容，修改下面的allValue或者后台的查询字段
                    var allValue=['item','attribute_name','large_category_name','small_category_name','large_area_name','part_area_name','building','floor','room'];//全部的要显示的一个任务的所有id
                    for(var i=0 ; i<data['data'].length ; i+=1){//循环，将所有物品分为条
                        var task='<li id="'+data['data'][i]['id']+'" style="background:rgb(51, 122, 183);color:#fff;cursor: pointer; border:0px;" class="list-group-item">';
                        task+= '<div id="'+i+'" onclick="itemsEdit(id)"> '
                        task += '<h4 class="media-heading" > <span id="category_id_'+i+'" hidden>'+data['data'][i]['category_id']+'</span><span id="position_id_'+i+'" hidden>'+data['data'][i]['position_id']+'</span>';
                        task += '<span id="item_'+i+'">'+data['data'][i][allValue[0]]+'</span>';
                        //task += '<a id='+i+' class="glyphicon glyphicon-pencil panel-title" onclick="itemsEdit(id)"></a>';
                        task += '<small style= "color:#fff;">&nbsp;<span id="attribute_name_'+i+'" >'+data['data'][i]['attribute_name']+'</span> <span id="large_category_name_'+i+'">'+data['data'][i]['large_category_name']+'</span> <span id="small_category_name_'+i+'">'+data['data'][i]['small_category_name']+'</span> ';
                        task += '<span id="large_area_name_'+i+'">'+data['data'][i]['large_area_name']+'</span>  <span id="part_area_name_'+i+'">'+data['data'][i]['part_area_name']+'</span> ';
                        task += '<span id="building_'+i+'">'+data['data'][i]['building']+'</span>  <span id="floor_'+i+'">'+data['data'][i]['floor']+'</span> <span id="room_'+i+'">'+data['data'][i]['room']+'</span></small></h4> ';
                        task += '(<span id="description_'+i+'">'+data['data'][i]['description']+'</span>)';
                        task+= '<input type="checkbox" id="categorycheck_'+data['data'][i]['category_id']+'_'+data['data'][i]['position_id']+'" style="margin-left:10px; height:20px;width:50px; display: none;" class="checkbox-inline pull-right" />';
                        task+='</div></li>';
                        allTaskList+=task;
                    }
                    $('#items').html(allTaskList);//输出到html中
                },
                error:function(){
                    console.log('error');
                }

            });

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

        /**
		 * @author：张政茂
         * @type {string}
         */
        var attribute_name = '';			//属性name
        var large_category_name = '';		//大类name
        var small_category_name = '';		//小类name
        var item = '';		                //物品
        var description = '';		        //物品详细描述
        var large_area_name = '';			//教学/生活name
        var part_area_name = '';			//系别name
        var building = '';			        //楼号
        var floor = '';			            //楼层name
        var room = '';			            //房间name



        //  函数功能：添加物品信息
        $("#additem").click(function(){

            item=$("#item").val();
            room=$("#room").val();
            floor=$("#floor").val();
            building=$("#building").val();
            description=$("#description").val();
            attribute_name=$("#attribute_name").val();
            part_area_name=$("#part_area_name").val();
            large_area_name=$("#large_area_name").val();
            large_category_name=$("#large_category_name").val();
            small_category_name=$("#small_category_name").val();
            $.ajax({
                type:'POST',
                url:'{{asset('/save')}}',
                async:false,
                data:{
                    item:item,
                    room:room,
                    floor:floor,
                    building:building,
                    description:description,
                    attribute_name:attribute_name,
                    part_area_name:part_area_name,
                    large_area_name:large_area_name,
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
                },
                error:function(error){
                    console.log(error);
                }
            });
        });


	</script>
</html>
@endsection