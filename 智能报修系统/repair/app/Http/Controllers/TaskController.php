<?php
	namespace App\Http\Controllers;
	
	use App\Common;
	use App\Task;
	use App\Users;
	use Illuminate\Http\Request;

	class TaskController extends Controller{
		
		/**
		*范留山
		*用户添加报修任务，
		*必须：传入用户id（userId）
					物品id（itemId）
					位置id（positionId）
		*可空：描述（desicription）
		*/
		public function addTask(Request $request){
			$uuid = $request->input('uuid');
			$large_area_name = $request->input('large_area_name');
			$part_area_name = $request->input('part_area_name');
			$building = $request -> input('building');
			$floor = $request -> input('floor');
			$room = $request -> input('room');
			$item = $request -> input('item');
			$description = $request -> input('description');
			$details = $request -> input('details');
			$users=new Users;
			$userId=$users->getUserId($uuid);
			$task = new Task();
			$dataResult = $task -> addTask($userId['id'],$large_area_name,$part_area_name,$building,$floor,$room,$item,$description,$details);
			if($dataResult['changeNumber']==2){
				$result =2;
				$remind = "未找到该物品";
			}elseif($dataResult['changeNumber']==3){
				$result = 3;
				$remind = "未找到该位置";
			}elseif($dataResult['changeNumber']==1){
				$result=1;
				$remind = '报修成功!';
			}else{
				$result = 0;
				$remind = '报修时数据库出现错误，请联系管理员。';
			}
			return Common::dataFormat($result,$remind,null);
		}
		
		/**
		*范留山
		*删除报修任务，
		*必须：传入任务id（taskId）
		*
		*/
		public function delectTask(Request $request){
			$taskId = $request->input('taskId');
			$task = new Task();
			$dataResult = $task -> delectTask($taskId);
			if($dataResult==0){
				$result = 0;
				$remind = '未找到该任务，或者该任务已经开始';
			}else{
				$result = 1;
				$remind = '删除成功';
			}
			return Common::dataFormat($result,$remind,null);
		}
		
		/**
		*范留山
		*用户修改报修任务
		*必须：传入用户id（userId）
					物品id（itemId）
					位置id（positionId）
		*可空：描述（desicription）
		*/
		public function taskUserChange(Request $request){
			$taskId = $request->input('taskId');
			$large_area_name = $request -> input('large_area_name');
			$part_area_name=$request ->input('part_area_name');
			$building = $request ->input('building');
			$floor = $request->input('floor');
			$room=$request->input('room');
			$item=$request->input('item');
			$description = $request -> input('description');
			$details = $request->input('details');
			$task = new Task();
			$changeResult = $task -> taskUserChange($taskId,$large_area_name,$part_area_name,$building,$floor,$room,$item,$details,$description);
			if ($changeResult['changeNumber']==1){
				$result = 1;
				$remind = "修改成功";
			}elseif($changeResult['changeNumber']==0){
				$result = 2;
				$remind = "物品已经维修，如有问题请重新报修";
			}elseif($changeResult['changeNumber']==3){
				$result =3;
				$remind = "未找到该物品";
			}elseif($changeResult['changeNumber']==4){
				$result = 4;
				$remind = "未找到该位置";
			}else{
				$result = 0;
				$remind = "修改失败";
			}
			return Common::dataFormat($result,$remind,$changeResult);
		}
		
		
		/**
		*范留山
		*修改工人任务（改变报修状态）
		*必须：任务id（taskId）
				状态id（state）
		*/
		public function taskWorkerChange(Request $request){
			$taskId = $request->input('taskId');
			$state = $request -> input('state');
			$workerId = $request->input('workerId');
			//根据要修改成的任务状态，构造用来检索的任务状态
			if($state==1){
				$oldState=0;
			}elseif($state==2||$state==3){
				$oldState=1;
			}
			//任务状态如果输入了非1、2、3的数，返回提示
			if($state!=1&&$state!=2&&$state!=3){
				return Common::dataFormat(3,"state传值只能为1、2或3",null);
			}
			$task = new Task();
			$changeResult = $task -> taskWorkerChange($workerId,$taskId,$state,$oldState);
			if ($changeResult['changeNumber']==1){
				$result = 1;
				$remind = "任务状态修改成功";
			}elseif($changeResult['changeNumber']==0){
				$result = 2;
				$remind = "任务状态修改失败";
			}else{
				$result = 0;
				$remind = "任务状态修改失败";
			}
			return Common::dataFormat($result,$remind,$changeResult);
		}


		/**
		*范留山
		*显示用户报修的信息列表
		*必须：用户id（userId）
		*/
		public function findUserTask(Request $request){
			$uuid = $request->input('uuid');

			$users=new Users;
			$userId=$users->getUserId($uuid);
			$task = new Task();
			$userSelfTasks = $task -> findUserTask($userId['id']);
			if($userSelfTasks==null){
				$result = 0;
				$remind = "还没有报修任务";
			}else{
				$result = 1;
				$remind = "查询用户报修信息列表成功";
			}
			return Common::dataFormat($result,$remind,$userSelfTasks);
		}
		
		/**
		*范留山
		*工人端显示报修信息列表
		*必须：工人id（workerId） 
		*	选择要看的任务类型（state）：0未开始的（可接单的），1工人自己正在做的和延时的，2完成的
		*返回
		*/
		public function findWorkerTask(Request $request){
			$workerId = $request->input('workerId');
			$state = $request->input('state');
			$task = new Task();
			$taskState = $task ->findWorkerTask($workerId,$state);
			return Common::dataFormat(1,'查找工人任务列表成功',$taskState);
		}
		
		
		/**
		*范留山
		*查找任务列表
		*给管理端显示，加入对任务索引，根据：区域，用户、物品类型、时间
		*可选	索引search如:['large_area'=>2]
		*		时间段
		*/
		public function findTaskList(Request $request){
			$search = $request->input('search');
			$betweenTime = $request -> input('betweenTime');
			$task = new Task();
			$allTask = $task->findTaskList($search,$betweenTime);

			//构建比例
			//取出所有任务中的检索字段（除了时间）
			$searchs=array();
			$searchsCount=array();
			$allSearch = ['large_area_name','part_area_name','attribute_name','large_category_name','small_category_name'];
			foreach($allTask['list'] as $oneTask){
				foreach($allSearch as $aSearch){
					$searchs[$aSearch][]=$oneTask[$aSearch];
				}
			}
			//构建成echars所需的数据格式
			$graphData = array();
			$number1=0;
				foreach($searchs as $aSearch){
					$searchsCount = array_count_values($aSearch);
					$number2=0;
					foreach ($searchsCount as $key=>$value) {
						$graphData[$allSearch[$number1]][$number2]['name'] = $key;
						$graphData[$allSearch[$number1]][$number2]['value'] = $value;
						$number2 += 1;
					}
					$number1+=1;
				}

			$graphData['this_number']=count($allTask['list']);
			$graphData['allNumber'] = $allTask['allNumber'];
			return Common::dataFormatGraph(1,'查询任务列表成功',$allTask['list'],$graphData);
		}
		
		/**
		*范留山
		*查找任务详细信息
		*必须 任务id（taskId）
		*/
		public function findTaskAllInformation(Request $request){
			$taskId = $request->input('taskId');
			$task = new Task();
			$allInformations =$task -> findTaskAllInformation($taskId);
			if($allInformations==null){
				$result = 0;
				$remind = "该任务已经被删除";
			}else{
				$result = 1;
				$remind = "查询用户报修信息列表成功";
			}
			return Common::dataFormat($result,$remind,$allInformations);
		}

		public function getImage(Request $request){
			$data = $request->input('image');
			$base46Image=explode(',', $data)[1];
			$img = base64_decode($base46Image);
			$a = file_put_contents('recognitionImages.jpg', $img);
			return $a;
		}

		public function findBottomInformation(Request $request){
			$data = $request->input('data');
			$id = $request->input('id');
			$task= new Task();

			if($data!="F"){
				$sqlPosition="";
				$sqlItem="";
				$data=json_decode($data,true);
				$num=0;
				$positionVal=[];
				$itemVal=[];
				foreach($data as $key=>$value){
					if($num<5){
						if($num!=count($data)-1){
							$sqlPosition=$sqlPosition.$key."=? and ";
							$positionVal[]=$value;
						}else{
							$sqlPosition=$sqlPosition.$key."=?";
							$positionVal[]=$value;
						}
					}
					if(count($data)==5){
						$sqlItem="first";
					}elseif($num>=5){
						if($num!=count($data)-1){
							$sqlItem=$sqlItem.$key."=? and ";
							$itemVal[]=$value;
						}else{
							$sqlItem=$sqlItem.$key."=?";
							$itemVal[]=$value;
						}
					}
					$num+=1;
				}
                $information=$task->findBottomInformation($sqlPosition,$sqlItem,$positionVal,$itemVal,$id);
			}else{
                $information=$task->findBottomInformation($data,false,false,false,$id);
			}
            return Common::dataFormat(1,"查找成功",$information);
		}
//		//测试用
//		public function ttry(){
//			$a = new Task();
//			return ['s'=>$a->ttry()];
//
//			//cookie的设置和获取
//			//			$cookie = Cookie::make('test', 'Hello, Laramist', 10);
////			return $request->cookie('test');
////			return response()->make('index')->withCookie($cookie);
//		}
	}