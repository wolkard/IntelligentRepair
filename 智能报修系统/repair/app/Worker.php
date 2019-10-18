<?php
/**
 * Created by PhpStorm.
 * User: n
 * Date: 2017/5/17 0017
 * Time: 19:55
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    protected $table = 'worker';

    protected $guarded = ['id'];

    public function addWorker($name,$age,$sex,$type,$area,$telephone){
        Worker::create([
            'name' => $name,
            'age' => $age,
            'sex' => $sex,
            'type' => $type,
            'area' => $area,
            'telephone' => $telephone
        ]);
        $return = 1;
        return $return;
    }

    public function delectWorker($ids){
        $result = Worker::whereIn('id',$ids)->update([
            'is_del'=>1
        ]);
        return $result;
    }
    public function changeWorkInformation($id,$change){
        $result = Worker::where('id',$id)->update($change);
        return $result;
    }
    public function findWorkInformation(){
        $return = Worker::where('is_del',0)->select('id','name','age','sex','type','area','telephone','head')->get();
        return $return;
    }

    //根据openid查找电话号码
    public function workLogin($openId){
        $result = Worker::where('openid',$openId)->select('id')->first();
        return $result;
    }
    public function workLogin2($telephone,$openid){
        $result = Worker::where('telephone',$telephone)->update(['openid' => $openid]);
        return $result;
    }
    public function telephone($telephone){
        $result = Worker::where('telephone',$telephone)->select('id')->first();
        return $result;
    }
}