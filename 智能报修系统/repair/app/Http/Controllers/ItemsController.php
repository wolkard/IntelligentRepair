<?php
/**
 * Created by PhpStorm.
 * User: n
 * Date: 2017/5/20 0020
 * Time: 14:53
 */

namespace App\Http\Controllers;


use App\Common;
use App\Item_category;
use App\Item_position;
use App\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemsController extends Controller
{

    /**
     * @param Request $request
     * @return mixed
     */
    public function findItemInformation(Request $request){
        $all = $request->input('search');
        $startTime = $request->input('betweenTime')[0];
        $endTime = $request->input('betweenTime')[1];
        if ($all != null){
            //判断前台传来的约束条件属于哪个表，并将约束条件分离。
            $all_category = Item_category::first();
            $all_position = Position::first();
            $constraintCategory = array();
            $constraintPosition = array();
            foreach ($all as $key=>$value){
                if (array_key_exists($key.'_id',$all_category['attributes'])){
                    $constraintCategory[$key.'_id'] = $value;
                }
                elseif (array_key_exists($key.'_id',$all_position['attributes'])){
                    $constraintPosition[$key.'_id'] = $value;
                }
            }
            //调用模型函数，将符合条件的id筛选出来。
            $Item_position = new Item_position();
            $itemIdTime = $Item_position->findItem($startTime,$endTime);
//        var_dump($itemIdTime);
            $Item_category = new Item_category();
            $itemIdCategorys = $Item_category->findItem($constraintCategory);
//        var_dump($itemIdCategorys);
            $position = new position();
            $itemIdPositions = $position->findItem($constraintPosition);
//        var_dump($itemIdPositions);
            //取符合三种条件的id数组的交集，并查询。
            $allId = array_intersect($itemIdPositions,$itemIdCategorys,$itemIdTime);
            $return = DB::table('item_position')
                ->join('position','position.id','=','item_position.position_id')
                ->join('item_category','item_position.category_id','item_category.id')
                ->whereIn('item_position.id',$allId)
                ->orderBy('item_position.created_at','DESC')
                ->get()
            ;
        }
        else{
            $Item_position = new Item_position();
            $itemIdTime = $Item_position->findItem($startTime,$endTime);
            $return = DB::table('item_position')
                ->join('position','position.id','=','item_position.position_id')
                ->join('item_category','item_position.category_id','item_category.id')
                ->whereIn('item_position.id',$itemIdTime)
                ->orderBy('item_position.created_at','DESC')
                ->get();
        }
        if ($return != null){
            $remind = '成功';
            $result = 1;
        }
        else{
            $remind = '失败';
            $result = 0;
        }
        return  Common::dataFormat($result,$remind,$return);

    }
    
    //张政茂，添加物品信息******************************************需修改！！！！！！！！！！！！！！！！！！！
    public function addItem(Request $request)
    {
        $image = $request->input('image');
        $attribute_id = $request->input('attribute_id');
        $attribute_name = $request->input('attribute_name');
        $large_category_id = $request->input('large_category_id');
        $large_category_name = $request->input('large_category_name');
        $small_category_id = $request->input('small_category_id');
        $small_category_name = $request->input('small_category_name');
        $item = $request->input('item');
        $description = $request->input('description');
        
		$item_Category = new Item_Category();
        $item_Category->addItem($image,$attribute_id,$attribute_name,$large_category_id,$large_category_name,$small_category_id,$small_category_name,$item,$description);

        return Common::dataFormat(1,'储存资产',null);
    }

    //葛操，删除物品信息
    public function delectItem(Request $request)
    {
        $category_ids = $request->input('category_id');
        $position_ids = $request->input('position_id');
        $item = new Item_category();
        $result = $item->delectItem($category_ids,$position_ids);
        if($result){
            $remind = '删除成功';
        }else{
            $remind = '删除失败';
        }

        return Common::dataFormat($result,$remind,$data = null);
    }

    public function items()
    {
        $test = DB::table('item_category')
            ->join('item_position','item_position.category_id','=','item_category.id')
            ->join('position','item_position.position_id','=','position.id')
            ->get();

        return view('item.adminItem',
            [
                'items'=>$test,

            ]
        );
    }
	//修改物品信息
    public function changeItemInformation(Request $request)
    {
        $category_id = $request->input('category_id');
        $attribute_name = $request->input('attribute_name');
        $large_category_name = $request->input('large_category_name');
        $small_category_name = $request->input('small_category_name');
        $item = $request->input('item');
        $description = $request->input('description');

        $position_id = $request->input('position_id');
        $large_area_name = $request->input('large_area_name');
        $part_area_name = $request->input('part_area_name');
        $building = $request->input('building');
        $floor = $request->input('floor');
        $room = $request->input('room');

        $item_table = new Item_category();
        $position_table = new Position();
        $result = $item_table->changeItemInformation($category_id,$attribute_name,$large_category_name,$small_category_name,$description,$item);
        $result1 = $position_table->changeItemInformation($position_id,$large_area_name,$part_area_name,$building,$floor,$room);
        if($result&&$result1){
            $remind = '修改成功';
        }else{
            $remind = '修改失败';
        }
        return Common::dataFormat($result,$remind,$data = null);
    }

    public function test()
    {
        $position = DB::table('position')->get();
        $items = Item_category::get();
        $item_position = Item_position::get();


            $test = DB::table('item_category')
                    ->join('item_position','item_position.category_id','=','item_category.id')
                    ->join('position','item_position.position_id','=','position.id')
            ->get();
            foreach ($test as $ttest)
            {
               dd($ttest->attribute_name);
            }

    }

    /**
     * @auther 张政茂
     * 添加物品资产
     * @param Request $request
     * @return result{
     *              result:1/0,
     *              remind:添加成功/失败,
     *              data:null
     *          }
     */
    public function save(Request $request)
    {
        $image=1;
        $coordinate=1;
        //$image=$request->input('image');
        $item=$request->input('item');
        $room=$request->input('room');
        $floor=$request->input('floor');
        $building=$request->input('building');
        //$coordinate=$request->input('coordinate')
        $description=$request->input('description');
        $part_area_name=$request->input('part_area_name');
        $attribute_name=$request->input('attribute_name');
        $large_area_name=$request->input('large_area_name');
        $large_category_name=$request->input('large_category_name');
        $small_category_name=$request->input('small_category_name');

        //向item_category表添加数据
        $Item_Category = new Item_Category();

        //向模型中传递数据，返回对应的id
        $attribute_id = $Item_Category->getAttribute_id($attribute_name);
        $large_category_id = $Item_Category->getLarge_category_id($large_category_name);
        $small_category_id = $Item_Category->getSmall_category_id($small_category_name);

        //利用orm将得到的数据传递给模型
        $result1 = $Item_Category->addItem($image,$attribute_id,$large_category_id,$small_category_id,$attribute_name,$large_category_name,$small_category_name,$item,$description);

        //获得当前具体的id数值
        $category_id = $result1->id;

        //向position添加数据
        $Position = new Position();

        //向模型中传递数据，返回对应的id
        /*$large_area_id = $Position->getLarge_area_id($large_area_name);
        $part_area_id = $Position->getPart_area_id($part_area_name);*/
        $large_area_id = $Position::select('large_area_id')
            ->where('large_area_name',$large_area_name)
            ->first();
        $part_area_id = $Position::select('part_area_id')
            ->where('part_area_name',$part_area_name)
            ->first();
        //利用orm将得到的数据传递给模型
        $result2 = $Position->addPosition($large_area_name,$part_area_name,$coordinate,$building,$floor,$room);

        //获得当前具体的id数值
        $position_id = $result2->id;

        $Item_Position = new Item_position();

        //向模型中传递数据
        $result = $Item_Position->addId($position_id,$category_id);


        //整理返回值
        if($result1&&$result2&&$result)
        {
            $remind = '添加成功';
        }else{
            $remind = '添加失败';
        }
        return Common::dataFormat($result,$remind,$data = null);

    }

    
}