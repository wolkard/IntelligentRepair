<?php
/**
 * Created by PhpStorm.
 * User: n
 * Date: 2017/5/15 0015
 * Time: 22:01
 */

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Position extends Model
{
    protected $table = 'position';

    protected $guarded = ['id'];

    public $timestamps = false;

    /**
     * @param $mac_intensity
     * @param $large_area_name
     * @param $part_area_name
     * @param $building
     * @param $floor
     * @param $room
     */
    public function addPosition($large_area_name,$part_area_name,$coordinate,$building,$floor,$room){

        $judgeLarge = Position::where('large_area_name',$large_area_name)->first();
        $judgePart = Position::where('part_area_name',$part_area_name)->first();
        //判断large_area_name与part_area_name是否已存在，是则取其id存入，否则取id最大值加一存入。
        if ($judgeLarge != null){
            $large_area_id = $judgeLarge->large_area_id;
        }
        else{
            $large_area_id = Position::where('id','>','0')->max('large_area_id')+1;
        }
        if ($judgePart != null){
            $part_area_id = $judgePart->part_area_id;
        }
        else{
            $part_area_id = Position::where('id','>','0')->max('part_area_id')+1;
        }

        $return = Position::firstOrCreate([
            'large_area_id' => $large_area_id,
            'large_area_name' => $large_area_name,
            'part_area_id' => $part_area_id,
            'part_area_name' => $part_area_name,
            /*'$coordinate'=>0,*/
            'building' => $building,
            'floor' => $floor,
            'room' => $room
        ]);
        return $return;
        /*$wifi = array();
        foreach ($mac_intensity as $arr){
            $BSSID = $arr[0];
            $level = $arr[1];
            $wifi[$BSSID] = $level;
        }
        arsort($wifi);
        $reJson = array();
        if (count($wifi)>=11){
            $i = 0;
            foreach ($wifi as $key=>$value){
                $json = array();
                $json['mac'] = $key;
                $json['level'] = $value;
                $reJson[] = $json;
                if ($i == 10){
                    break;
                }
                $i += 1;
            }
            $jsons = json_encode($reJson);
            DB::table('symbol')->insert([
                ['position_id'=>$return->id,'mac_intensity'=>$jsons]
            ]);
        }
        else{
            foreach ($wifi as $key=>$value){
                $json = array();
                $json['mac'] = $key;
                $json['level'] = $value;
                $reJson[] = $json;
            }
            $jsons = json_encode($reJson);
            DB::table('symbol')->insert([
                ['position_id'=>$return->id,'mac_intensity'=>$jsons]
            ]);
        }*/

    }

    /**
     * @param $constraintPosition
     * @return array
     */
    public function findItem($constraintPosition){
        $positionId = Position::where($constraintPosition)->select('id')->get();
        $positionIds = array();
        foreach ($positionId as $id){
            $positionIds[] = $id->id;
        }
        $itemIdPosition = Item_position::whereIn('position_id',$positionIds)->select('id')->get();
        $itemIdPositions = array();
        foreach ($itemIdPosition as $id){
            $itemIdPositions[] = $id->id;
        }
        return $itemIdPositions;
    }

}