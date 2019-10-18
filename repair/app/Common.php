<?php
	namespace App;

	use Illuminate\Database\Eloquent\Model;

	class Common extends Model{

		/**
		*范留山
		*管理员端任务列表处显示检索列表
		*
		*/
		public function taskSearchList(){

			//获取不重复的、根据任务状态检索的物品属性
			$attributeSearch =Task::join('item_category',function($table){
				$table->on('item_category.id','=','task.item_id');
			})
			->select('item_category.attribute_id','item_category.attribute_name')
			//->whereIn('task.state',$state)
			->distinct()
			->get();
			//获取不重复的、根据任务状态检索的物品大类
			foreach($attributeSearch as $oneAttributeSearch ){

				$largeCategorySearch = Task::join('item_category',function($table){
					$table->on('item_category.id','=','task.item_id');
				})
				->select('item_category.large_category_id','item_category.large_category_name')
				->where('item_category.attribute_id',$oneAttributeSearch['attribute_id'])
				//->whereIn('task.state',$state)
				->distinct()
				->get();
				//获取不重复的、根据任务状态检索的物品小类
				$i=0;
				//if($k<3){
				foreach($largeCategorySearch as $oneLargeCategorySearch){
					$smallCategorySearch = Task::join('item_category',function($table){
						$table->on('item_category.id','=','task.item_id');
					})
					->select('item_category.small_category_id','item_category.small_category_name')
					->where('item_category.large_category_id',$oneLargeCategorySearch['large_category_id'])
					//->whereIn('task.state',$state)
					->distinct()
					->get();
					//向物品大类中添加物品小类
					$oneLargeCategorySearch['large_category_aontain']=$smallCategorySearch;
				}
				//向物品属性中添加物品大类
				$oneAttributeSearch['attribute_aontain']=$largeCategorySearch;

			}
			//}

			//获取位置大类
			$largeAreaSearch = Task::join('position',function($table){
				$table->on('position.id','=','task.position_id');
			})
			->select('position.large_area_id','position.large_area_name')
			//->whereIn('task.state',$state)
			->distinct()
			->get();
			//获取不重复的、根据任务状态检索的位置小类
			foreach($largeAreaSearch as $oneLargeAreaSearch){
				$partAreaSearch = Task::join('position',function($table){
					$table->on('position.id','=','task.position_id');
				})
				->select('position.part_area_id','position.part_area_name')
				->where('position.large_area_id',$oneLargeAreaSearch['large_area_id'])
				//->whereIn('task.state',$state)
				->distinct()
				->get();
				//向物品大类中添加小类
				$oneLargeAreaSearch['large_area_aontain'] = $partAreaSearch;
			}
			$allList['position'] = $largeAreaSearch;
			$allList['item'] = $attributeSearch;
			return $allList;
		}


		//管理员物品资产列表处显示资产检索
		public function itemSearchList(){
			//
            //获取不重复的检索的物品属性
            $attributeSearch =Item_position::join('item_category',function($table){
                $table->on('item_category.id','=','item_position.category_id');
            })
                ->select('item_category.attribute_id','item_category.attribute_name')
                ->distinct()
                ->get();
            //获取不重复的检索的物品大类
            foreach($attributeSearch as $oneAttributeSearch ){
                $largeCategorySearch = Item_position::join('item_category',function($table){
                    $table->on('item_category.id','=','item_position.category_id');
                })
                    ->select('item_category.large_category_id','item_category.large_category_name')
                    ->where('item_category.attribute_id',$oneAttributeSearch['attribute_id'])
                    ->distinct()
                    ->get();
                //获取不重复的索的物品小类
                foreach($largeCategorySearch as $oneLargeCategorySearch){
                    $smallCategorySearch = Item_position::join('item_category',function($table){
                        $table->on('item_category.id','=','item_position.category_id');
                    })
                        ->select('item_category.small_category_id','item_category.small_category_name')
                        ->where('item_category.large_category_id',$oneLargeCategorySearch['large_category_id'])
                        ->distinct()
                        ->get();
                    //向物品大类中添加物品小类
                    $oneLargeCategorySearch['large_category_aontain']=$smallCategorySearch;
                }
                //向物品属性中添加物品大类
                $oneAttributeSearch['attribute_aontain']=$largeCategorySearch;

            }
            //}

            //获取位置大类
            $largeAreaSearch = Item_position::join('position',function($table){
                $table->on('position.id','=','item_position.position_id');
            })
                ->select('position.large_area_id','position.large_area_name')
                ->distinct()
                ->get();
            //获取不重复的、根据任务状态检索的位置小类
            foreach($largeAreaSearch as $oneLargeAreaSearch){
                $partAreaSearch = Item_position::join('position',function($table){
                    $table->on('position.id','=','item_position.position_id');
                })
                    ->select('position.part_area_id','position.part_area_name')
                    ->where('position.large_area_id',$oneLargeAreaSearch['large_area_id'])
                    //->whereIn('task.state',$state)
                    ->distinct()
                    ->get();
                //向物品大类中添加小类
                $oneLargeAreaSearch['large_area_aontain'] = $partAreaSearch;
            }
            $allList['position'] = $largeAreaSearch;
            $allList['item'] = $attributeSearch;
            return $allList;
		}
		
		/**
		*范留山
		*返回内容格式化
		*/
		public static function dataFormat($result,$remind,$data){
			return response()->json([
				'result'=>$result,
				'remind'=>$remind,
				'data'=>$data
			]);
		}
        public static function dataFormatGraph($result,$remind,$data,$graphData){
            return response()->json([
                'result'=>$result,
                'remind'=>$remind,
                'data'=>$data,
                'graphData'=>$graphData
            ]);
        }
		//图片识别
		public function recognition($url){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回
			curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回
			$results = curl_exec($ch);
			curl_close($ch);
			//$results = "9_风扇,1.0;142_投影仪,1.62711e-11;126_水龙头,1.19429e-11;9_风扇,1.0;142_投影仪,1.62711e-11;126_水龙头,1.19429e-11;";
		/*	echo $results;*/
			$resultArray = explode(';',$results);
			$finallyResult = array();
			$number = 0;
			foreach($resultArray as $oneResult){
				if($number<3){
					$oneResultArray = explode(',',$oneResult);
					if($oneResultArray[1]>=0){
						$itemId = explode('_',$oneResultArray[0])[0];
						$finallyResult[$number]['id']=$itemId;
						$finallyResult[$number]['possibility']=$oneResultArray[1];
						$taskInformation = Item_category::select('attribute_id',
							'attribute_name',
							'large_category_id',
							'large_category_name',
							'small_category_id',
							'small_category_name',
							'item',
							'description',
							'image'
						)->where('id',$itemId)
							->first();
						$finallyResult[$number]['information']=$taskInformation;
					}
					$number+=1;
				}
			}
			return $finallyResult;
		}
		public function location($mac,$url){
			$data = json_encode(array('mac'=>$mac));
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					'Content-Type: application/json; charset=utf-8',
					'Content-Length: ' . strlen($data)
				)
			);
			$response = curl_exec($ch);
			$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			return ['result'=>$httpCode,'data'=>$response];
		}
	}
