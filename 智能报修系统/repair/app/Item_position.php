<?php
	namespace App;

	use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Facades\DB;
	class Item_position extends Model{
		protected $table = 'item_position';  //指定表名
		
		protected $primaryKey = 'id';  //指定主键
		
		protected $guarded = ['id'];  //不可批量添加的字段（黑名单）
			
		public function findItem($startTime,$endTime){
	        if ($startTime != null and $endTime != null){
	            $timeId = Item_position::where('created_at','>=',$startTime)->where('created_at','<=',$endTime)->select('id')->get();
	        }
	        elseif ($endTime != null){
	            $timeId = Item_position::where('created_at','<=',$endTime)->select('id')->get();
	        }
	        elseif ($startTime != null){
	            $timeId = Item_position::where('created_at','>=',$startTime)->select('id')->get();
	        }
	        else{
	            $timeId = Item_position::select('id')->get();
	        }
	        $itemIdTime = array();
	        foreach ($timeId as $id){
	            $itemIdTime[] = $id->id;
	        }
	        return $itemIdTime;
	    }
	    
	    //葛操
        public function item_category()
    {
        return $this->hasOne('App\Item_category','id' ,'category_id');
    }

        //接收到控制器的数据后，利用create方法将数据存入数据库中
        public function addId($position_id,$category_id)
        {

            $remind = Item_Position::firstOrCreate([
					'position_id'=>$position_id,
					'category_id'=>$category_id
				]);

            //返回数值：result/result1/result3
            if($remind)
            {
                return 1;
            }else{
                return 0;
            }
        }


	}