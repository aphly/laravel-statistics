<?php

namespace Aphly\LaravelStatistics\Controllers\Front;

use Aphly\Laravel\Exceptions\ApiException;
use Aphly\LaravelStatistics\Models\Statistics;
use Aphly\LaravelStatistics\Models\StatisticsHost;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function add(Request $request)
    {
        if($request->isMethod('post')) {
            $input = $request->all();
            $input['ipv4'] = $request->ip();
            $url = parse_url($input['url']);
            if($input['appid'] && $url){
                $statisticsHost = StatisticsHost::where('appid',$input['appid'])->where('status',1)->firstOrError();
                if($url['host']==$statisticsHost->host){
                    $input['url'] = '/'.basename($input['url']);
                    $input['host_id'] =$statisticsHost->id;
                    $info = Statistics::where('host_id',$statisticsHost->id)->where('ipv4',$input['ipv4'])->where('url',$input['url'])->first();
                    if(!empty($info) && $info->created_at->isToday()){
                        $info->increment('view');
                    }else{
                        Statistics::create($input);
                    }
                }
            }
        }
        throw new ApiException(['code'=>0,'msg'=>'success']);
    }
}
