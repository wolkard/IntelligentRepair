<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Item_category extends Model{
    protected $table = 'item_category';  //指定表名

    protected $primaryKey = 'id';  //指定主键

    protected $guarded = ['id'];  //不可批量添加的字段（黑名单）



    //根据物品id查找物品资产详细信息
    public function findItemInformation($itemId){
        $itemInformation = Item_category::select(
                'image',
                'attribute_id',
                'attribute_name',
                'large_category_id',
                'large_category_name',
                'small_category_id',
                'small_category_name',
                'item',
                'details'
            )
            ->where('id' , $itemId)
            ->first();
        return $itemInformation;
    }

    //聂恒奥，查找物品id
    public function findItem($constraintCategory){
        $categoryId = Item_category::where($constraintCategory)->select('id')->get();
        $categoryIds = array();
        foreach ($categoryId as $id){
            $categoryIds[] = $id->id;
        }
        $itemIdCategory = Item_position::whereIn('category_id',$categoryIds)->select('id')->get();
        $itemIdCategorys = array();
        foreach ($itemIdCategory as $id){
            $itemIdCategorys[] = $id->id;
        }
        return $itemIdCategorys;
    }
	    

    
    //葛操
        public function item_position()
    {
        return $this->belongsTo('App\Item_position','category_id','id');
    }
    //葛操
    //暂时放到这！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！11
    public function delectItem($category_ids,$position_ids)
    {
        $flag = 0;
        $i = 0;
        foreach ($category_ids as $category_id)
        {
            $num = Item_position::where(['category_id'=>$category_id,'position_id'=>$position_ids[$i]])->delete();
            if($num>0)
            {
                $flag++;
            }
            $i++;
        }
        if($flag == $i) {
            $result = 1 ;
        }else{
            $result = 0 ;
        }
        return $result;
    }
    public function  changeItemInformation($category_id,$attribute_name,$large_category_name,$small_category_name,$description,$item)
    {


            $category = Item_category::find($category_id);
            $bool = $category->update([
                'attribute_name'=>$attribute_name,
                'large_category_name'=>$large_category_name,
                'small_category_name'=>$small_category_name,
                'description'=>$description,
                'item'=>$item
            ]);
            if($bool){
                $result = 1;
            }else{
                $result = 0;
            }

        return $result;
    }

    /**
     * @author 张政茂
     *
     */

    //从控制器接收到各个参数并存入数据库中
    public function addItem($image,$attribute_id,$large_category_id,$small_category_id,$attribute_name,$large_category_name,$small_category_name,$item,$description)
    {
        $remind = Item_Category::firstOrCreate(
            [
                'image'=>$image,
                'attribute_id'=>$attribute_id,
                'large_category_id'=>$large_category_id,
                'small_category_id'=>$small_category_id,
                'attribute_name'=>$attribute_name,
                'large_category_name'=>$large_category_name,
                'small_category_name'=>$small_category_name,
                'item'=>$item,
                'description'=>$description]
        );

        return $remind;
    }


    /**
     * @author张政茂
     * @param $attribute_name
     * @return $attribute_id
     *
     *
     */
    public function getAttribute_id($attribute_name)
    {
        //使用构造查询器获取数据库表指定列attribute_name并转换为数组
        $attribute_names = DB::table('item_category')->pluck('attribute_name')->toArray();

        //判断数组中是否含有$attribute_name
        if(in_array($attribute_name,$attribute_names))
        {
            //若数组中包含$attribute_name，在数据库中获取其对应的$attribute_id，返回对象
            $attribute_id = DB::table('item_category')
                ->where('attribute_name',$attribute_name)
                ->get();

            //返回获取到的id
            return $attribute_id[0]->attribute_id;
        }else{
            //若数组不包含$attribute_name,直接获取指定列id，并转换为数组
            $attribute_ids = DB::table('item_category')->select('attribute_id')->get()->toArray();

            //查询数组中最大的id
            $attribute_id = 1;
            if($attribute_ids)
            {
                $pos=array_search(max($attribute_ids),$attribute_ids);
                $attribute_id = $attribute_ids[$pos]->attribute_id;
                //为$attribute_id赋值
                $attribute_id++;

                //返回id
                return $attribute_id;
            }else{
                //返回id
                return $attribute_id;
            }

        }

    }

    /**
     * @author张政茂
     * @param $large_category_name
     * @return $large_category_id
     */
    public function getLarge_category_id($large_category_name)
    {
        //使用构造查询器获取数据库表指定列large_category_name并转换为数组
        $large_category_names = DB::table('item_category')->pluck('large_category_name')->toArray();

        //判断数组中是否含有$large_category_name
        if(in_array($large_category_name,$large_category_names))
        {
            //若数组中包含$large_category_name，在数据库中获取其对应的$large_category_id，返回对象
            $large_category_id = DB::table('item_category')
                ->where('large_category_name',$large_category_name)
                ->get();

            //返回获取到的id
            return $large_category_id[0]->large_category_id;
        }else{
            //若数组不包含$large_category_name,直接获取指定列id，并转换为数组
            $large_category_ids = DB::table('item_category')->select('large_category_id')->get()->toArray();

            //查询数组中最大的id
            $large_category_id = 1;
            if($large_category_ids)
            {
                $pos=array_search(max($large_category_ids),$large_category_ids);
                $large_category_id = $large_category_ids[$pos]->large_category_id;
                //为$large_category_id赋值
                $large_category_id++;

                return $large_category_id;
            }else{
                return $large_category_id;
            }
        }
    }

    /**
     * @author张政茂
     * @param $small_category_name
     * @return $small_category_id
     */
    public function getSmall_category_id($small_category_name)
    {
        //使用构造查询器获取数据库表指定列small_category_name并转换为数组
        $small_category_names = DB::table('item_category')->pluck('small_category_name')->toArray();

        //判断数组中是否含有$small_category_name
        if(in_array($small_category_name,$small_category_names))
        {
            //若数组中包含$small_category_name，在数据库中获取其对应的$small_category_id，返回对象
            $small_category_id = DB::table('item_category')
                ->where('small_category_name',$small_category_name)
                ->get();

            //返回获取到的id
            return $small_category_id[0]->small_category_id;
        }else{
            //若数组不包含$small_category_name,直接获取指定列id，并转换为数组
            $small_category_ids = DB::table('item_category')->select('small_category_id')->get()->toArray();

            //查询数组中最大的id
            $small_category_id = 1;
            if($small_category_ids)
            {
                $pos=array_search(max($small_category_ids),$small_category_ids);
                $small_category_id = $small_category_ids[$pos]->small_category_id;
                //为$small_category_id赋值
                $small_category_id++;

                return $small_category_id;
            }else{
                return $small_category_id;
            }

        }
    }

	}