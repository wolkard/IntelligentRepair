<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Auth::routes();
Route::get('logout', 'Auth\LoginController@logout');

/*显示视图*/
//显示用户任务列表页面
Route::get('userTaskView',function(){
	return view('user.userTask');
});

//用户首页
Route::get('userHomeView',function(){
	return view('user.userHome');
});
//用户报修页面
Route::get('userReportView',function(){
	return view('user.reportTask');
});
//用户显示报修详情和修改报修任务页面
Route::get('userChangeReportView',function(){
	return view('user.changeReport');
});
//图片识别
Route::post('recognition',[
	'uses' => 'CommonController@recognition',
]);
//位置识别
Route::post('location',[
	'uses'=>'CommonController@location',
]);

//工人首页
Route::get('workerHomeView',function(){
	return view('worker.workerHome');
});
//工人查看完成的任务
Route::get('seeFinishTaskView',function(){
	return view('worker.seeFinishTask');
});
//工人查看可接取的任务
Route::get('seeReceiveTaskView',function(){
	return view('worker.seeReceiveTask');
});
//工人查看详细信息页面
Route::get('receiveTaskView',function(){
	return view('worker.receiveTask');
});


/**
 *范留山
 *任务相关
 */
//添加任务（用户报修）
Route::post('addTask',[
	'uses' => 'TaskController@addTask',
	'middleware' => 'addTask',
]);
//用户删除报修任务
Route::post('delectTask',[
	'uses' => 'TaskController@delectTask',
]);
//用户查找自己任务列表
Route::post('findUserTask',[
	'uses' => 'TaskController@findUserTask',
]);
//用户更改报修信息
Route::post('taskUserChange',[
	'uses' => 'TaskController@taskUserChange',
]);
//用户查找下级信息（报修校准时）
Route::post('findBottomInformation',[
	'uses' => 'TaskController@findBottomInformation',
]);
//查找用户uuid，确认是否登录过
Route::post('findUuid',[
	'uses' => 'UserController@findUuid',
]);

//接收图片
Route::post('getImage',[
	'uses' => 'TaskController@getImage',
]);

//工人更改任务状态
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!1提交之前先查找任务是否变化，若变化或任务被删除，则提示
Route::post('taskWorkerChange',[
	'uses' => 'TaskController@taskWorkerChange',
]);
//工人查找任务
Route::post('findWorkerTask',[
	'uses' => 'TaskController@findWorkerTask',
]);
//确认工人
Route::get('confirmWorkerTwo',[
	'uses' => 'WorkerController@confirmWorkerTwo',
]);
Route::get('confirmWorkerOne',[
	'uses' => 'WorkerController@confirmWorkerOne',
]);
//工人电话号码确认
Route::post('telephone',[
	'uses' => 'WorkerController@telephone',
]);
//查找任务详情
Route::post('findTaskAllInformation',[
	'uses' => 'TaskController@findTaskAllInformation',
]);


/**
 *葛操
 */
//用户进入系统
Route::post('/userEnter',['uses'=>'UserController@userEnter']);
/*//管理员删除用户信息
Route::post('/delectUser',['uses'=>'UserController@delectUser']);*/
//用户修改自己信息
Route::post('/changeUserInformation',['uses'=>'UserController@changeUserInformation']);
//查找用户信息
Route::post('/findUserInformation',['uses'=>'UserController@findUserInformation']);
Route::any('/userregister',
	['uses'=>'UserController@userregister']
);
Route::any('/items',
	['uses'=>'ItemsController@items']
);


Route::any('/user',
	['uses'=>'UserController@index']);


//将路由放入其中，登陆后可用
Route::group(['middleware' => 'auth'], function () {
	/**
	 *范留山
	 *任务相关
	 */
	/*管理员显示视图*/
	//管理员首页（管理员相关信息）
	Route::get('admin/home',function(){
		return view('admin.adminHome');
	});
	//管理员查找所有报修任务页面
	Route::get('admin/task',function(){
		return view('admin.adminTask');
	});
	//管理员查找所有物品资产
	Route::get("admin/items",function(){
		return view("admin.adminItem");
	});
	/*管理员后台请求*/
	//管理端查找任务列表
	Route::post('findTaskList',[
		'uses' => "TaskController@findTaskList",
	]);
	//显示任务检索列表
	Route::post('taskSearchList',[
		'uses'=> "CommonController@taskSearchList",
	]);
	//显示资产检索列表
	Route::post('itemSearchList',[
		'uses'=>"CommonController@itemSearchList",
	]);

	/**
	 *聂恒奥
	 */

	//管理端工人界面
	Route::get('admin/worker',function (){
		return view('admin.worker');
	});
	Route::group(['middleware' => ['web']], function(){
		//管理端添加工人信息
		Route::post('addWorker','WorkerController@addWorker');
		//管理端修改工人信息
		Route::post('changeWorkInformation','WorkerController@changeWorkInformation');
	});
	//管理端删除工人信息
	Route::post('delectWorker','WorkerController@delectWorker');
	//管理端查询工人列表
	Route::post('findWorkInformation','WorkerController@findWorkInformation');
	//管理端物品资产检索
	Route::post('findItemInformation','ItemsController@findItemInformation');



	/**田荣鑫*/
	//添加管理员
	Route::post('addadmin',['uses'=>'AdminController@addAdmin']);
	//查找管理员列表
	Route::post('findadmin',['uses'=>'AdminController@findAdminInformation']);
	//删除管理员
	Route::post('deladmin',['uses'=>'AdminController@deleteAdmin']);
	//管理员修改密码
	Route::post('changepassword',['uses'=>'AdminController@changePassword']);
	/*范留山，新增*/
	//修改管理员信息
	Route::post('changeAdminInformation',['uses'=>'AdminController@changeAdminInformation']);


	//聂恒奥，物品检索
	Route::post('findItemInformation','ItemsController@findItemInformation');
	//张政茂，添加物品
	Route::post('addItem',['uses'=>'ItemsController@addItem']);
	//张政茂，添加物品资产
	Route::post('save',['uses'=>'ItemsController@save']);
	//葛操，删除物品位置对应表
	Route::post('admin/delectItem',
		['uses'=>'ItemsController@delectItem']
	);
	#葛操，修改物品信息
	Route::post('admin/changeItemInformation',
		['uses'=>'ItemsController@changeItemInformation']
	);


});


// 聂恒奥
//工人登陆1
Route::post('workLogin','WorkerController@workLogin');
//工人登陆2
Route::post('workLogin2','WorkerController@workLogin2');
//添加位置信息
Route::any('addPosition','TestController@addPosition');






//测试用
Route::post('try',[
	'uses' => "TaskController@ttry",
]);


