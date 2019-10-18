<?php
/**
 * Created by PhpStorm.
 * User: n
 * Date: 2017/5/15 0015
 * Time: 19:48
 */

namespace App\Http\Controllers;




use App\Position;
use Illuminate\Http\Request;


class TestController extends Controller
{
    public function addPosition(Request $request){
      $callback = $request->input('callback');
      $mac_intensity = $request->input('znnz');
        $large_area_name = $request->input('large_area_name');
        $part_area_name = $request->input('part_area_name');
        $building = $request->input('building');
        $floor = $request->input('floor');
        $room = $request->input('room');
        $position = new Position();
        $position->addPosition($mac_intensity,$large_area_name,$part_area_name,$building,$floor,$room);
      //$arr = array('status'=>'200');      
      return response($callback.'('.json_encode($mac_intensity).')');
        

        /*
       
        */
    
    
    }
}