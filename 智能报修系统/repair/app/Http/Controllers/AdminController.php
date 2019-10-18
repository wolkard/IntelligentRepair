<?php

namespace App\Http\Controllers;


use App\Admin;
use App\Common;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController
{
    //添加管理员
    public function addAdmin(Request $request){
        $telephone = $request->input('telephone');
        $password = $request->input('password');
        $email = $request->input('email');
        $addAdmin = new Admin();
        $result = $addAdmin->addAdmin($telephone,$password,$email);
        if($result==-1){
        	$resultCode=2;
            $resultMsg='当前邮箱已经被注册';
        }elseif ($result){
            $resultCode=1;
            $resultMsg='添加管理员信息成功';
        }else{
            $resultCode=0;
            $resultMsg='添加管理员信息失败';
        }
        return  Common::dataFormat($resultCode,$resultMsg,$result);
    }

    //查找管理员详细信息。
    public function findAdminInformation()
    {
        $findAdminInfo = new Admin();
        $result = $findAdminInfo->findAdminInformation();
        if ($result){
            $resultCode=1;
            $resultMsg='获取管理员信息成功';
        }else{
            $resultCode=0;
            $resultMsg='获取管理员信息失败';
        }
        return Common::dataFormat($resultCode,$resultMsg,$result);
    }

    //删除管理员
    public function deleteAdmin(Request $request){
        $id = $request->input('id');
        $deleteAdmin = new Admin();
        $result = $deleteAdmin->deleteAdmin($id);
        if ($result){
            $resultCode=1;
            $resultMsg='删除管理员信息成功';
        }else{
            $resultCode=0;
            $resultMsg='删除管理员信息失败';
        }
        return Common::dataFormat($resultCode,$resultMsg,NULL);

    }

    //更改管理员密码
    public function changePassword(Request $request){
        $email = $request->input('email');
        $oldPassword = $request->input('oldPassword');
        $newPassword = $request->input('newPassword');
        $newPasswordAgain = $request->input("newPasswordAgain");
        if($newPassword!=$newPasswordAgain){
            return Common::dataFormat(3,"两次输入的密码不一致",NULL);
        }
        $changePassword = new Admin();
        $password = $changePassword->checkPassword($email);
        if (Hash::check($oldPassword,$password['password'])==false){
        	return Common::dataFormat(2,"原始密码错误",NULL);
        }
        
		$result = $changePassword->changePassword($email,$newPassword);
        if ($result==1){
            $resultCode=1;
            $resultMsg='更改管理员密码成功';
        }else{
            $resultCode=0;
            $resultMsg='更改管理员密码失败';
        }
        return Common::dataFormat($resultCode,$resultMsg,NULL);
    }

    function changeAdminInformation(Request $request){
        $email = $request->input("email");
        $password = $request->input("password");
        $telephone = $request->input("telephone");
        $admin=new Admin();
        $changeNumber=$admin->changeAdminInformation($email,$password,$telephone);
        if($changeNumber==0){
            $result=0;
            $remind="未找到该邮箱";
        }else if($changeNumber==1){
            $result=1;
            $remind="修改成功";
        }else{
            $result=2;
            $remind="未知错误";
        }
        return Common::dataFormat($result,$remind,null);
    }
}