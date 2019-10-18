<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
	<head>
		<meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
		<title>登陆</title>
        <link href="{{asset('css/admin/loginV2.css')}}" rel="stylesheet"/>
        <link href="{{asset('css/bootstrap.css')}}" rel="stylesheet"/>
	</head>
	<body>
    <img src="{{asset('images/admin/index.jpg')}}" id="background">
		<div class="logo" id="logo">
            <div class="login" id="login">
                <!--登陆框-->

                    <div id="app" class="panel-body aboutLogin" {{--style="background: white;width:250px;border-radius: 3%"--}}>
                        <form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
                            {{ csrf_field() }}
                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} autoClass">
                                <div class="col-md-12">
                                    <input id="email" type="email" class="form-control inputCss" name="email" value="{{ old('email') }}" placeholder="邮箱" required autofocus>

                                    @if ($errors->has('email'))
                                        <span class="help-block" style="font-size:10px;margin-bottom: -15px;!important;">
                                            <strong>邮箱或密码错误</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} autoClass">
                                <div class="col-md-12 ">
                                    <input id="password" type="password" class="form-control inputCss" name="password"  placeholder="密码"  required>

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>密码错误</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="checkbox-wrap">
                                <input type="checkbox"  name="choose" id="selfLogin" {{ old('remember') ? 'checked' : '' }} >
                                <label for="selfLogin" >自动登录</label>
                            </div>
                                <div class="col-md-10 col-md-offset-1">
                                    <button type="submit" class="btn btn-primary" style="background: #167c68;left:50px;margin-top:-32px;float: right;width:40%;height:40%;">
                                        登陆
                                    </button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
	</body>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{asset('js/jquery.js')}}"></script>
<script>
	$(document).ready(function(){
        noChange();//禁用放大缩小

    });
    var scrollFunc=function(e){
        e=e || window.event;
        if(e.wheelDelta && event.ctrlKey){//IE/Opera/Chrome
            event.returnValue=false;
        }else if(e.detail){//Firefox
            event.returnValue=false;
        }
    };
    function noChange(){//禁用放大缩小
        //注册事件
        if(document.addEventListener){
            document.addEventListener('DOMMouseScroll',scrollFunc,false);
        }
        window.onmousewheel=document.onmousewheel=scrollFunc;//IE/Opera/Chrome/Safari
    }

</script>
</html>
