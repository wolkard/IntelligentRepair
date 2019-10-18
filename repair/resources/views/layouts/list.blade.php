<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title></title>
        <script src="{{asset('js/jquery-3.2.1.min.js')}}"></script>
        <link href="{{asset('css/list-V8.css')}}" rel="stylesheet"/>
	</head>
	<body>
		<div class="menu" id="lists">
			<div id="Administrator"></div>
			<div id=""></div>
			<div id="list_home" class="list_item" onclick="checkChange(this.id);"></div>
			<div id="list_worker" class="list_item" onclick="checkChange(this.id);"></div>
			<div id="list_task" class="list_item" onclick="checkChange(this.id);"></div>
			<div id="list_items" class="list_item" onclick="checkChange(this.id);"></div>
			<div id="logout" class="list_item" onclick="self.location.href='{{asset('/logout')}}'"></div>
		</div>
        @yield('list')

	</body>
	<script>

        $(document).ready(function(){
            var url=document.URL.split('/');
            var urlLen=url.length;
			if(url[urlLen-1].charAt(url[urlLen-1].length - 1)=="#"){
				var page=url[urlLen-1].substr(0,url[urlLen-1].length-1);
			}else{
				var page=url[urlLen-1];
			}
            $("#list_"+page).addClass('listClicked');
			changeMenu(page);
        });

		function changeMenu(page){
			if(page=='worker'||page=='worker#'){
				$(".menu").css("background","#338e96");
			}else if(page=='home'||page=='home#'){
				$(".menu").css("background","#0ab27b");
			}else if(page=='task'||page=='task#'){
				$(".menu").css("background","#465b83");
			}else if(page=='items'||page=='items#'){
				$(".menu").css("background","rgb(51, 122, 183)");
			}
		}

        function autoChange(){
            var width= window.screen.availWidth;
            var height = window.screen.availHeight;

            var listWidthB=320/1366;
            var listWidth = listWidthB*width;

            $("#lists").css("height",height);
            $("#lists").css("width",listWidth);
        }
		//菜单被鼠标悬浮事件
		$(".list_item").hover(function(){
			$(this).addClass("listHover");
		});
		$(".list_item").mouseleave(function(){
			$(this).removeClass("listHover");
		});
		function checkChange (thisId){
			$(".list_item").removeClass("listClicked");
			$("#"+thisId).addClass("listClicked");
            document.cookie="listId="+thisId;
            if(thisId=='list_home'){
                self.location.href="{{asset('/admin/home')}}";
            }else if(thisId=='list_worker'){
                self.location.href="{{asset('/admin/worker')}}";
            }else if(thisId=='list_task'){
                self.location.href="{{asset('/admin/task')}}";
            }else if(thisId=='list_items'){
                self.location.href="{{asset('/admin/items')}}";
            }

		}
	</script>
</html>
