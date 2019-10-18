<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Common;

class CommonController extends Controller
{
	public function taskSearchList(Request $request){
		$common = new Common();
		$searchList = $common->taskSearchList();
		return Common::dataFormat(1,'查询报修任务检索列表成功',$searchList);
	}
	public function itemSearchList(Request $request){
		$common = new Common();
		$searchList = $common->itemSearchList();
		return Common::dataFormat(1,'查询资产检索列表成功',$searchList);
	}
	//图片识别
	public function recognition(Request $request){
		$common = new Common();
		$recognition=$common->recognition('http://localhost:5000/image');
		return Common::dataFormat(1,'图片识别成功',$recognition);
	}
	//位置识别
	public function location(Request $request)
	{
		$mac = $request->input('mac');
		$common = new Common();
		//$mac2='[{"mac":"0a:69:6c:76:fc:bb","level":"-45"},{"mac":"06:69:6c:76:fc:ba","level":"-47"},{"mac":"06:69:6c:76:fc:a6","level":"-51"},{"mac":"0a:69:6c:76:fc:a7","level":"-58"},{"mac":"0a:69:6c:77:09:cf","level":"-69"},{"mac":"06:69:6c:77:00:9e","level":"-72"}]';

		$url = "http://127.0.0.1:5000/location";
		$recognition=$common->location($mac,$url);
		if($recognition['result']==200){
			$result =1;
			$remind = "位置识别成功";
		}else{
			$result = 0;
			$remind = '位置识别失败';
		}
		return Common::dataFormat($result,$remind,$recognition['data']);
	}

}
