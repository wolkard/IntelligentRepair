<?php

namespace App\Http\Middleware;

use App\Task;
use App\Common;
use Closure;

class AddTaskMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
    	$itemId = $request -> input("itemId");
    	$positionId = $request -> input('positionId');
    	$findResult = Task::select("id")
		->whereRaw("item_id = ? and position_id = ?",[$itemId,$positionId])
		->first();
	if ($findResult==null){
		return $next($request);
	}else{
		$common = new Common();
		$result = $common->dataFormat(2,"改物品已经报修,请勿重复报修",null);
		return $result;
	}
    	
    
    }
}
