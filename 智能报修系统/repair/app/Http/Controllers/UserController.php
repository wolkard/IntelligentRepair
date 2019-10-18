<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-5-17
 * Time: 下午 11:05
 */
namespace App\Http\Controllers;


use App\Common;
use App\Users;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = Users::get();
        return view('user.index',
            ['users'=>$users]);
    }
    public function userregister()
    {
        return view('user.userregister');
    }
    public function usertest()
    {
        return view('user.usertest');
    }
    //增  
    public function userEnter(Request $request)
    {
        $uuid = $request->input('uuid');
        $number = $request->input('number');
        $telephone = $request->input('telephone');
        $state = $request->input('state');
        $users = new Users();


        if($state) {
            $result = $users->userEnter($uuid, $number, $telephone);
            if ($result) {
                $remind = '注册成功';
            } else {
                $remind = '注册失败';
            }
            return Common::dataFormat($result, $remind, $data = $result);
        }
        else{
            return Common::dataFormat($result = 0,$remind = '登录失败',$data = null);
        }

    }

    //删
    public function delectUser(Request $request)
    {
        $openid = $request->input('openid');
        $users = new Users();
        $result = $users->delectUser($openid);
        if($result) {
            $remind = '成功';
        }else{
            $remind = '失败';
        }
        return Common::dataFormat($result,$remind,$data = null);
    }
    //改       
    public function changeUserInformation(Request $request)
    {
        $openid = $request->input('openid');
        $number = $request->input('number');
        $telephone = $request->input('telephone');
        $users = new Users();
        $result = $users->changeUserInformation($openid,$telephone,$number);
        if($request){
            $remind = '成功';
        }else{
            $remind = '失败';
        }
        return Common::dataFormat($result,$remind,$data = null);
    }
    //查找用户信息
    public function findUuid(Request $request)
    {
        $uuid = $request->input('uuid');

        $users = new Users();
        $data = $users->findUuid($uuid);

        if($data){
            $result = 1;
            $remind = '成功';
        }else{
            $result = 0;
            $remind = '失败';
            $data = null;
        }
        return Common::dataFormat($result,$remind,$data);
    }

}