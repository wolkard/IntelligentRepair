<?php
	namespace App;

	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Notifications\Notifiable;
	use Illuminate\Support\Facades\Hash;
	use Illuminate\Foundation\Auth\User as Authenticatable;
	class Admin extends Authenticatable{
		use Notifiable;
		protected $table = 'admin';  //指定表名
		
		protected $primaryKey = 'id';  //指定主键
		
		protected $guarded = ['id'];  //不可批量添加的字段（黑名单）

		/**
		* The attributes that should be hidden for arrays.
		*
		* @var array
		*/
	/*	protected $hidden = [
			'password', 'remember_token',
		];*/
		
    /**
     *
     *@author Dain
     *增加管理员   addAdmin
     *@param  $telephone 前台发送的手机号
     *@param  $password 前台发送的密码
     *@ param  $email前台发送的邮箱
     */
    public function addAdmin($telephone, $password,$email)
    {
		$find = Admin::select('id')
			->where('email',$email)
			->first();
		if ($find==null){
			$result_insert = Admin::insert([
				'email'=>$email,
				'telephone' => $telephone,
				'password' => Hash::make($password),
			]);
			return 1;
		}else{
			return -1;
		}
	}



    /**
     * @auther Dain
     * 获取管理员列表，获取的管理员手机号
     *@return {
     *              'ResultCode':'1'or'0'
     *              'ResultMsg':'成功'or'失败'
     *              'Data'=>$adminInfo
     */
    public function findAdminInformation()
    {
        $adminInfo =DB::table('admin')
        	->select('id','telephone','email','head','is_del','created_at')
			->where('is_del',"!=","1")
            ->get();
        return $adminInfo;
    }

    /**
     * @author 田荣鑫
     * 删除管理员（deleteAdministrators）
     * @param $telephone
     * @return {
     *              'ResultCode':'1'or'0'
     *              'ResultMsg':'成功'or'失败'
     *              'Data'=>null
     */
    public function  deleteAdmin($id)
    {
        //删除操作 数据库为更新is_del数值为1
        $adminDelResult = DB::table('admin')
            ->whereIn('id',$id)
            ->update(['is_del'=>1]);
        return $adminDelResult;
    }

    /**
     * @author 田荣鑫
     * 修改管理员密码  验证密码后确认
     * @param $telephon  前台传值到后台经过md5加密值，用来判断哪位管理员。
     * @param $lastPassword  前台获取的原来的管理员密码
     * @param $newPassword  前台获取的新的密码
     * @return {
     *              'ResultCode':'1'or'0'
     *              'ResultMsg':'成功'or'失败'
     *              'Data'=>null
     *
     */
    public function checkPassword($email)
    {
        //验证旧密码
        $password = Admin::select ('password')
        	->where ('email',$email)
            ->first();
        return $password;
    }
    public function changePassword($email,$newPassword)
    {
        //修改密码，基于email

        $result = DB::table('admin')
            ->where('email',$email)
            ->update(['password'=>Hash::make($newPassword)]);
        return $result;

    }
    
    public function changeAdminInformation($email,$password,$telephone){
		if($password!=null){
			$result=Admin::where('email',$email)
				->update(['password'=>Hash::make($password),'telephone'=>$telephone]);
		}else{
			$result=Admin::where('email',$email)
				->update(['telephone'=>$telephone]);
		}
		return $result;
	}
	

}
