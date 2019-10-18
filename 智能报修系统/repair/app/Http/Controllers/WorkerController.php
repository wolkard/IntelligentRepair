<?php
/**
 * Created by PhpStorm.
 * User: n
 * Date: 2017/5/17 0017
 * Time: 19:54
 */

namespace App\Http\Controllers;

use Redirect;
use App\Common;
use App\Worker;
use Illuminate\Http\Request;

class WorkerController extends Controller
{
    //添加工人
    public function addWorker(Request $request){
        $this->validate($request,[
            'name' => 'required',
            'telephone' => 'required|numeric',
            'area' => 'required|numeric',
            'age' => 'numeric|nullable',
        ],[
            'required'=>':attribute不能为空',
            'numeric'=>':attribute应为数字',
        ],[
            'name' => '姓名',
            'type' => '类型',
            'age' => '年龄',
            'telephone' => '手机号',
            'area' => '所属区域',
        ]);
        $name = $request->input('name');
        $age = $request->input('age');
        $sex = $request->input('sex');
        $type = $request->input('type');
        $area = $request->input('area');
        $telephone = $request->input('telephone');
        $worker = new Worker();
        $result = $worker->addWorker($name,$age,$sex,$type,$area,$telephone);
        if ($result = 1){
            $remind = '成功';
        }
        else{
            $remind = '失败';
        }
        return Common::dataFormat($result,$remind,null);
    }
    //删除工人
    public function delectWorker(Request $request){
        $ids = $request->input('id');
        $worker = new Worker();
        $result = $worker->delectWorker($ids);
        if ($result = count($ids)){
            $remind = '成功';
            $result = 1;
        }
        else{
            $remind = '失败';
            $result = 0;
        }
        return Common::dataFormat($result,$remind,null);
    }
    //修改工人信息
    public function changeWorkInformation(Request $request){
        $this->validate($request,[
            'name' => 'required',
            'telephone' => 'required|numeric',
            'area' => 'required|numeric',
            'age' => 'numeric|nullable',
        ],[
            'required'=>':attribute不能为空',
            'numeric'=>':attribute应为数字',
        ],[
            'name' => '姓名',
            'type' => '类型',
            'age' => '年龄',
            'telephone' => '手机号',
            'area' => '所属区域',
        ]);
        $id = $request->input('id');
        $all = $request->all();
        unset($all['id']);
        $all = array_filter($all);
        $change = $all;
        $worker = new Worker();
        $result = $worker->changeWorkInformation($id,$change);
        if ($result == 1){
            $remind = '成功';
        }
        else{
            $remind = '失败';
        }
        return Common::dataFormat($result,$remind,$result);
    }
    //查询工人列表
    public function findWorkInformation(){
        $worker = new Worker();
        $return = $worker->findWorkInformation();
        if ($return != null){
            $remind = '成功';
            $result = 1;
        }
        else{
            $remind = '失败';
            $result = 0;
        }
        return Common::dataFormat($result,$remind,$return);
    }
    //移动端工人登陆1
    public function workLogin(Request $request){
        $openid = $request->input('openid');
        $worker = new Worker();
        $result = $worker->workLogin($openid);
        if ($result != null){
            return redirect('workHome');
//            dd($result) ;
        }
        else{
            return redirect('workLogin1');
//            dd($result) ;
        }
    }
    //移动端工人登陆2
    public function workLogin2(Request $request){
        $telephone = $request->input('telephone');
        $openid = $request->input('openid');
        $worker = new Worker();
        $result = $worker->workLogin2($telephone,$openid);
        if ($result == 1){
//            return redirect('workHome');
            dd('workhome');
        }
        else{
//            return redirect('home');
            dd('home');
        }
        return $result;
    }

/*    private  $result=null;
    public function confirmWorker(){
        //请求url地址
        $corpid='wwca50ec780fa208a2';
        $corpsecret='8GrlswjfmYnPZoiEfYo0vU37TdAn-kWOcgQ3DWeLXx8';

        $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=".$corpid."&corpsecret=".$corpsecret;
        //初始化curl
        $ch = curl_init($url);
        //3.设置参数
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//跳过证书验证
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  // 从证书中检查SSL加密算法是否存在
        //4.调用接口
        $res = curl_exec($ch);
        if(curl_errno($ch)){
            var_dump(curl_error($ch));
        }
        $result = json_decode($res,1);
        //5.关闭curl
        curl_close($ch);
        $this->result=$result;
        return $result['access_token'];
    }*/

    //获取access_token
    public function getAccessToken(){
        $corpid='wwca50ec780fa208a2';
        $corpsecret='8GrlswjfmYnPZoiEfYo0vU37TdAn-kWOcgQ3DWeLXx8';
        $file = file_get_contents("./wx_cache/access_token.json",true);
        $result = json_decode($file,true);
        /*return ['time'=>time(),'expires'=>$result['expires']];*/
        if (time() > $result['expires']){
            $data = array();
            $data['access_token'] = self::getNewToken($corpid,$corpsecret);
            $data['expires']=time()+7000;
            $jsonStr =  json_encode($data);
            $fp = fopen("./wx_cache/access_token.json", "w");
            fwrite($fp, $jsonStr);
            fclose($fp);
            return $data['access_token'];
        }else{
            return $result['access_token'];
        }
    }

    //发送get请求
    public function https_request ($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $out = curl_exec($ch);
        curl_close($ch);
        return  json_decode($out,true);
    }

    //获取新的access_token
    public function getNewToken($corpid,$corpsecret){
        $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=".$corpid."&corpsecret=".$corpsecret;
        $access_token_Arr =  self::https_request($url);
        return $access_token_Arr['access_token'];
    }

    //获取所有部门列表，从中获获取部门id：
    public function getAllDepartment (){
        $access_token=self::getAccessToken();
        $url= "https://qyapi.weixin.qq.com/cgi-bin/department/list?access_token=".$access_token;
        $departmentList =  self::https_request($url);
        return $departmentList['department'][0]['id'];
    }

    //根据access_token和$department_id获取工人id：userid
    public function getWorkerId(){
        $access_token=self::getAccessToken();
        $department_id=self::getAllDepartment();
        $fetchChild=1;//1/0：是否递归获取子部门下面的成员
        $url = "https://qyapi.weixin.qq.com/cgi-bin/user/simplelist?access_token=".$access_token."&department_id=".$department_id."&fetch_child=".$fetchChild;
        $workerId =  self::https_request($url);
        return $workerId;
    }


    public function confirmWorkerOne(Request $request){
        $appid='wwca50ec780fa208a2';
        $toUrl="http%3a%2f%2f219.218.160.81%2frepair%2fpublic%2fconfirmWorker";
        $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=".$toUrl."&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect";
        self::https_request($url);
    }

    //获取登陆工人本人的信息，最后带着数据重定向到工人首页，获取到UserId。
    public function confirmWorkerTwo(Request $request){
        $code= $request->input('code');
        $access_token=self::getAccessToken();
        $url="https://qyapi.weixin.qq.com/cgi-bin/user/getuserinfo?access_token=".$access_token."&code=".$code;
        $workerInformation=self::https_request($url);
        $workerInformation=json_encode($workerInformation);
        $userId=json_decode($workerInformation,true)['UserId'];
        return  self::findUserId($userId,$workerInformation);
        return view('worker.workerHome',compact('workerInformation'));
    }

    //判断此userId是否已经存在，
    //如果存在，返回此userid相关信息
    //不存在，跳转到填写电话号码页面。
    public function findUserId($openId,$workerInformation){
        $worker = new Worker();
        $workerId = $worker->workLogin($openId);
        if ($workerId!=null){
            //此账号已经登陆过
            return view('worker.workerHome',compact('workerId','workerInformation'));
        }else{
            //跳转输入电话号码页面
            return view('worker.writeTelephone',compact('openId'));
        }
    }

    //输入电话号码验证
    //输入正确电话号码后，将openid写入数据库
    public function telephone(Request $request){
        $telephone = $request->input('telephone');
        $openId=$request->input('openId');
        $worker=new Worker();
        $result = $worker->telephone($telephone);
        if($result==null){
            $remind = '未找到该电话号码';
        }else{
            $remind = '成功登陆';
            $result = $worker->workLogin2($telephone,$openId);//写入openId
        }
        return Common::dataFormat($result,$remind,null);
    }

}