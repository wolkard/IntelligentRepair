<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-5-17
 * Time: 下午 11:08
 */
namespace App;

use Illuminate\Database\Eloquent\Model;


class Users extends Model
{
    protected $table = 'user';  //指定表名

    protected $primaryKey = 'id';  //指定主键

    protected $guarded = ['id'];  //不可批量添加的字段（黑名单）


    //查找用户openid是否存在
    public function useTest($openid)
    {
        $user = Users::where('openid',$openid)
            ->first();
        return $user;
    }
    //用户进入
    public function userEnter($uuid,$number,$telephone)
    {
        $newUser = Users::create([
            'uuid' => $uuid,
            'number' => $number,
            'telephone' => $telephone,
            'is_del' => 0
        ]);
        if($newUser) {
            $result = 1;
        }else{
            $result = 0;
        }
        return $result;
    }
    public function delectUser($openid)
    {
        $user = Users::where('openid','=',$openid)->first();
       // dd($user);
        $user->is_del = 1;

        if($user->save()){
            $result = 1;
        }else{
            $result = 0;
        }
        return $result;
    }
    public function changeUserInformation($openid,$telephone,$number)
    {
        $user = Users::where('openid','=',$openid)->first();
        $user->telephone = $telephone;
        $user->number = $number;
        if($user->save()){
            $result = 1;
        }else{
            $result = 0;
        }
        return $result;
    }
    //查找用户信息
    public function findUuid($uuid)
    {
        $user = Users::select('telephone','number')
            ->where('uuid',$uuid)
            ->first();
        if($user) {
            return $user;
        }else{
            return false;
        }
    }
    public function getUserId($uuid){
        $user = Users::select('id')
            ->where('uuid',$uuid)
            ->first();
        return $user;
    }

}