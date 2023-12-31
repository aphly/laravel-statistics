<?php

namespace Aphly\LaravelStatistics\Controllers\Front;

use Aphly\Laravel\Exceptions\ApiException;
use Aphly\LaravelStatistics\Models\Statistics;
use Aphly\LaravelStatistics\Models\StatisticsSite;
use Aphly\LaravelStatistics\Models\StatisticsIpv4;
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
                try{
                    $StatisticsSite = StatisticsSite::where('appid',$input['appid'])->where('status',1)->firstOrError();
                    if($url['host']==$StatisticsSite->host){
                        $input['url'] = '/'.basename($input['url']);
                        $input['site_id'] =$StatisticsSite->id;
                        $info = Statistics::where('site_id',$StatisticsSite->id)->where('ipv4',$input['ipv4'])->where('url',$input['url'])->first();
                        if(!empty($info) && $info->created_at->isToday()){
                            $info->increment('view');
                        }else{
                            $ip_int = ip2long($input['ipv4']);
                            $ipv4_lib = StatisticsIpv4::where('ip_start_int','<=',$ip_int)->where('ip_end_int','>=',$ip_int)->first();
                            $input['country_iso'] = !empty($ipv4_lib)?$ipv4_lib->country_iso:'';
                            Statistics::create($input);
                        }
                    }
                }catch (\Exception $e){
                    throw new ApiException(['code'=>1,'msg'=>'fail']);
                }
            }
        }
        throw new ApiException(['code'=>0,'msg'=>'success']);
    }
}
