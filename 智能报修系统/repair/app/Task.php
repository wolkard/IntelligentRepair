<?php
	namespace App;

    use App\Item_category;
	use App\Position;
	use App\Worker;
	use Illuminate\Database\Eloquent\Model;

	class Task extends Model{
		protected $table = 'task';  //指定表名
		
		protected $primaryKey = 'id';  //指定主键
		
		protected $guarded = ['id'];  //不可批量添加的字段（黑名单）


		/**
		*
		*范留山
		*添加报修任务
		*/
		public function addTask($userId,$large_area_name,$part_area_name,$building,$floor,$room,$item,$description,$details){
            $itemId = Item_category::select('id')
                ->whereRaw('item = ? and description = ?',[$item,$description])
                ->first();
            $positionId = Position::select('id')
                ->whereRaw('large_area_name = ? and part_area_name = ? and building = ? and floor = ? and room = ?',[$large_area_name,$part_area_name,$building,$floor,$room])
                ->first();
            if($itemId==null){
                return ['changeNumber'=>2];
            }elseif($positionId==null){
                return ['changeNumber'=>3];
            }else{
                $newTask = Task::create([
                    'user_id' => $userId,
                    'item_id' => $itemId['id'],
                    'position_id' => $positionId['id'],
                    'details' => $details,
                    'state' => 0
                ]);
                return ['changeNumber' => 1];
            }
		}
		
		/**
		*范留山
		*用户删除未开始的报修信息
		*/
		public function delectTask($taskId){
			$deleTask = Task::whereRaw('state = 0 and id = ?',[$taskId])
				->delete();
			return $deleTask;
		}
		
		
		/**
		*范留山
		*用户修改报修信息
		*
		*/
		public function taskUserChange($taskId,$large_area_name,$part_area_name,$building,$floor,$room,$item,$details,$description){
            $itemId = Item_category::select('id')
				->whereRaw('item = ? and description = ?',[$item,$description])
				->first();
            $positionId = Position::select('id')
				->whereRaw('large_area_name = ? and part_area_name = ? and building = ? and floor = ? and room = ?',[$large_area_name,$part_area_name,$building,$floor,$room])
                ->first();
            if($itemId==null){
                return ['changeNumber'=>3];
            }elseif($positionId==null){
                return ['changeNumber'=>4];
            }else{
                $newTask = Task::whereRaw("id = ? " , [$taskId])
                    ->update([
                        "item_id" =>$itemId['id'],
                        "position_id"=>$positionId['id'],
                        "details"=>$details,
                    ]);
                return ['changeNumber'=>$newTask];
            }

		}
		
		/**
		*范留山
		*工人改变任务状态
		*/
		public function taskWorkerChange($workerId,$taskId,$state,$oldState){
			$newTask = Task::where('id' , $taskId)
				->where('state',$oldState)
				->update(['work_id'=>$workerId,'state'=>$state,'end_time'=>date("Y-m-d H:i:s")]);
			return ['changeNumber' => $newTask];
		}
		
		
		/**
		*范留山
		*显示用户报修的信息
		*/
		public function findUserTask($userId){
			$userSelfTasks = Task::join('item_category', 'task.item_id', '=', 'item_category.id')
					->join('position','position.id','task.position_id')
					->join('user','user.id','task.user_id')
				->select(
						'position.large_area_name',
						'position.part_area_name',
						'position.building',
						'position.floor',
						'position.room',
						'task.id',
						'task.state',
						'item_category.item'
					)
					->where('user_id',$userId)
					->orderBy('task.created_at')
					->get();
			//$allInformation = $this ->findPositionItem($userSelfTasks);
			return $userSelfTasks;
		}
		
//		/**
//		*范留山
//		*查找任务所对应的物品和位置详细信息
//		*参数 informations	  任务信息
//		*/
//		public function findPositionItem($informations){
//			$position = new Position();
//			$itemCategory = new Item_category();
//			$allInformation = array();
//			foreach ( $informations as $information){
//				$oneInformation = array();
//				$oneInformation= $position -> findPositionInformation($information['position_id']);
//				$arrayOneInformation = json_decode(json_encode($oneInformation),true);//利用json编码函数，将对象转换为数组
//				$itemCategory=$itemCategory->findItemInformation($information['item_id']);
//				$arrayOneInformation['description']=$information['description'];//加入任务信息
//				$arrayItemCategory = json_decode(json_encode($itemCategory),true);//利用json编码函数，将对象转换为数组
//				foreach($arrayItemCategory as $key=>$value){
//					$arrayOneInformation[$key]=$value;
//				}
//				$allInformation[]=$arrayOneInformation;//加入位置信息
//			}
//			return $allInformation;
//		}
//		

		
		/**
		*范留山
		*查找任务列表
		*/
		public function findWorkerTask($workerId,$state){
//			$doingWorkTaskList = Task::select('id','user_id','item_id','position_id','description','created_at')
//				->whereRaw('state = 1 and work_id = ?',[$workerId])
//				->get();
//			$delayedWorkTaskList = Task::select('id','user_id','item_id','position_id','description','created_at')
//				->whereRaw('state = 3 and work_id = ?',[$workerId])
//				->get();
//			$notBeginWorkTaskList = Task::select('id','user_id','item_id','position_id','description','created_at')
//				->where('state', 0)
//				->get();
//			$finshWorkTaskList = Task::select('id','user_id','item_id','position_id','description','created_at')
//				->whereRaw('state = 2')
//				->get();
//			//查找具体位置信息和物品信息，并添加到数组中（每个任务一个数组）
//			$doingAllInformation = $this ->findPositionItem($doingWorkTaskList);
//			$delayedAllInformation = $this ->findPositionItem($delayedWorkTaskList);
//			$notBeginAllInformation = $this ->findPositionItem($notBeginWorkTaskList);
//			$finshAllInformation = $this ->findPositionItem($finshWorkTaskList);
//			return array('doingWorkTaskList'=>$doingAllInformation,
//				'delayedWorkTaskList'=>$delayedAllInformation,
//				'notBeginWorkTaskList'=>$notBeginAllInformation,
//				'finshWorkTaskList'=>$finshAllInformation
//			);
//return $workerId;
			//构建sql语句
			if($workerId!=null){
				$allTasks = Task::join('item_category', 'task.item_id', '=', 'item_category.id')
					->join('position','position.id','task.position_id')
					->select(
						'task.details',
						'task.created_at',
						'position.large_area_name',
						'position.part_area_name',
						'position.building',
						'position.floor',
						'position.room',
						'task.id',
						'task.state',
						'item_category.item',
						'item_category.description'
					)
					->where('task.work_id',$workerId)
					->whereIn('task.state',$state)
					->orderBy('task.created_at')
					->get();
			}else{
				$allTasks = Task::join('item_category', 'task.item_id', '=', 'item_category.id')
					->join('position','position.id','task.position_id')
					->select(
						'task.details',
						'task.created_at',
						'position.large_area_name',
						'position.part_area_name',
						'position.building',
						'position.floor',
						'position.room',
						'task.state',
						'task.id',
						'item_category.item',
						'item_category.description'
					)
					->whereIn('task.state',$state)
					->orderBy('task.created_at')
					->get();
			}

			//任务表、物品详情表、位置表联合查询
			return $allTasks;
		}
		
		/**
		*范留山
		*查找任务列表
		*向管理端显示，加入对任务的索引，根据：区域，用户、物品类型、时间
		*/
		public function findTaskList($search,$betweenTime){
			
			//构建sql语句和 值
			$sql ='';
			$finallyValues = array();
			$lenSearch = count($search);
			
			//如果进行了位置或者物品检索
			//如果添加检索，只需要添加判断$key所在哪个表即可
			if($lenSearch!=0){
				$number = 0;
				foreach($search as $key=>$value){
					//判断是哪个表中的字段
					if($key == 'large_area' || $key == 'part_area' ){
						$table = 'position.';
					}elseif($key == 'attribute' ||$key == 'large_category' || $key == "small_category" ){
						$table = 'item_category.';
					}elseif($key == 'state'){
						$table = 'task';
					}
					//如果是第一组，则没有and
					if ($number==0){
						$sql=$sql.$table.$key.'_id=? ';
						$finallyValues[]=$value;
					}else{
						$sql=$sql.' and '.$table.$key.'_id=? ';
						$finallyValues[]=$value;
					}
					$number+=1;
				}
			}
			
			//如果使用了时间段检索
			if($betweenTime!=null){
				//sql是否存在，如果存在，则使用最前面有and的，否则用没and的
				if($sql!=''){
					$sql=$sql." and task.created_at between ? and ? ";
				}else{
					$sql=$sql." task.created_at between ? and ? ";
				}
				//将两个值存入一个数组中
				$finallyValues = array_merge($finallyValues,$betweenTime) ;
			}
			
			//如果sql语句存在，进行whereRaw条件约束
			//都进行了任务创建时间由新到旧的排序
			if($sql!=''){
				//任务表、物品详情表、位置表联合查询
				$tasks = Task::join('item_category', 'task.item_id', '=', 'item_category.id')
					->join('position','position.id','task.position_id')
					->select(
						'position.large_area_name',
						'position.part_area_name',
						'position.building',
						'position.coordinate',
						'position.floor',
						'position.room',
						'task.evalution',
						'task.id',
						'task.details',
						'task.created_at',
						'task.end_time',
						'task.state',
						'item_category.item',
						'item_category.attribute_name',
						'item_category.large_category_name',
						'item_category.small_category_name',
						'item_category.description'
					)
					->whereRaw($sql,$finallyValues)
					->orderBy('task.created_at')
					->get()
					->toArray();
			}else{
				//任务表、物品详情表、位置表联合查询
				$tasks = Task::join('item_category',function($table){
					$table->on('task.item_id', '=', 'item_category.id');
				})->join('position',function($table){
					$table->on('position.id','task.position_id');
				})->select('task.evalution',
					'position.large_area_name',
					'position.part_area_name',
					'position.building',
					'position.coordinate',
					'position.floor',
					'position.room',
					'task.id',
					'task.details',
					'task.created_at',
					'task.end_time',
					'task.state',
					'item_category.item',
					'item_category.attribute_name',
					'item_category.large_category_name',
					'item_category.small_category_name',
					'item_category.description'
				)->orderBy('task.created_at','DESC')
					->get()
					->toArray();
			}
			
//			$allInformation =$this -> findPositionItem($allTasks);
//			$allInformation = json_decode(json_encode($allInformation),true);//利用json编码函数，将对象转换为数组
//			$listInformation=array();
//			$theList = array();
//			foreach($allInformation as $information){
//				foreach($information as $key=>$value){
//					if($key == 'item' || $key == 'large_area_name' || $key =='part_area_name' || $key == 'building' || $key=='room' ||$key=='floor'){
//						$listInformation[$key] = $value;
//					} 
//				}
//				$theList[]=$listInformation;
//			}
			//$allNumber = Task::select('');
			$allTasks = Task::join('item_category',function($table){
				$table->on('task.item_id', '=', 'item_category.id');
			})->join('position',function($table){
				$table->on('position.id','task.position_id');
			})->select('task.id')
				->get()
				->toArray();
			$allNumber = count($allTasks);
			$task['allNumber'] = $allNumber;
			$task['list'] = $tasks;

			return $task;
		}
		
		
		
		/**
		*范留山
		*查找任务详细信息
		*/
		public function findTaskAllInformation($taskId){
			//任务表、物品详情表、位置表联合查询
			$allInformation = Task::join('item_category','item_category.id','task.item_id')
				->join('position','position.id','task.position_id')
				->select('task.evalution',
					'task.details',
					'task.created_at',
					'task.end_time',
					'task.state',
					'position.large_area_name',
					'position.part_area_name',
					'position.building',
					'position.coordinate',
					'position.floor',
					'position.room',
					'item_category.image',
					'item_category.attribute_name',
					'item_category.large_category_name',
					'item_category.small_category_name',
					'item_category.item',
					'item_category.description'
				)
				->where('task.id',$taskId)
				->first();
//			//调用查找具体位置，物品详情方法
//			$informations = $this -> findPositionItem(['allInformation'=>$allInformation]);//因为有遍历，所以需要传入一个数组
//			//加入查找工人具体信息方法，将工人id转换为工人详细信息
//			$allInformation = json_decode(json_encode($allInformation),true);//利用json编码函数，将对象转换为数组
//			foreach($allInformation as $key=>$value){
//				//用条件去除work_id,item_id,position_id
//				if($key!='work_id'&&$key!='item_id'&&$key!='position_id'){
//					$informations[0][$key]=$value;
//				}
//			}
			return $allInformation;
		}

		public function findBottomInformation($sqlPosition,$sqlItem,$positionVal,$itemVal,$id){
			$positionInformation=Null;
			$itemInformation=Null;
			if($sqlItem==false){
				if($sqlPosition=="F"){
					$positionInformation=Position::/*join('item_category','item_category.id','task.item_id')
					->join('position','position.id','task.position_id')*/
					select($id)
						->Distinct()
						->get();
				}else{
					$positionInformation=Position::select($id)
						->whereRaw($sqlPosition,$positionVal)
						->Distinct()
						->get();

				}

			}else{
				if($sqlItem!=false){
					if($sqlItem!="first"){
						$itemInformation=Item_category::select($id)
							->whereRaw($sqlItem,$itemVal)
							->Distinct()
							->get();
					}else {
						$itemInformation = Item_category::select($id)
							->Distinct()
							->get();
					}
				}else{
					$itemInformation=Null;
				}
			}
			$information=[];
			$information["positionInformation"]=$positionInformation;
			$information["itemInformation"]=$itemInformation;

			return $information;
		}
		
////测试用
//		public function ttry(){
//			$attributeSearch =Task::join('item_category',function($table){
//				$table->on('item_category.id','=','task.item_id');
//			})
//			->select(
//				'item_category.small_category_id',
//				'item_category.small_category_name'
//			)
//			->distinct(
//					'item_category.attribute_id',
//				'item_category.attribute_name',
//					'item_category.large_category_id',
//				'item_category.large_category_name'
//			)
//
//			->get();
//			return $attributeSearch;
//		}
	}
