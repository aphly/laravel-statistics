<?php

namespace Aphly\LaravelStatistics\Controllers\Admin;

use Aphly\Laravel\Exceptions\ApiException;
use Aphly\Laravel\Models\Breadcrumb;

use Aphly\LaravelStatistics\Models\Statistics;
use Aphly\LaravelStatistics\Models\StatisticsHost;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public $index_url = '/statistics_admin/statistics/index';
    public $p_url = '/statistics_admin/host/index';

    private $currArr = ['name'=>'统计','key'=>'statistics'];

    public function index(Request $request)
    {
        $host_id = $request->query('host_id','');
        $res['statisticsHost'] = StatisticsHost::where('id',$host_id)->firstOrError();
        $res['search']['ip'] = $request->query('ip', '');
        $res['search']['string'] = http_build_query($request->query());
        $res['list'] = Statistics::when($res['search'],
                            function ($query, $search) {
                                if($search['ip']!==''){
                                    $query->where('ipv4', $search['ip']);
                                }
                            })
                        ->where('host_id', $host_id)
                        ->orderBy('id', 'desc')
                        ->Paginate(config('admin.perPage'))->withQueryString();
        $res['breadcrumb'] = Breadcrumb::render([
            ['name'=>$this->currArr['name'].'管理','href'=>$this->p_url],
            ['name'=>$res['statisticsHost']->host,'href'=>$this->index_url.'?host_id='.$res['statisticsHost']->id],
        ]);
        return $this->makeView('laravel-statistics::admin.statistics.index', ['res' => $res]);
    }

    public function detail(Request $request)
    {
        $host_id = $request->query('host_id','');
        $res['statisticsHost'] = StatisticsHost::where('id',$host_id)->firstOrError();
        $res['info'] = Statistics::where('id',$request->query('id',0))->firstOrNew();
        $res['breadcrumb'] = Breadcrumb::render([
            ['name'=>$this->currArr['name'].'管理','href'=>$this->p_url],
            ['name'=>$res['statisticsHost']->host,'href'=>$this->index_url.'?host_id='.$res['statisticsHost']->id],
            ['name'=>'详情','href'=>'/statistics_admin/'.$this->currArr['key'].'/detail?id='.$res['info']->id.'&host_id='.$res['statisticsHost']->id]
        ]);
        return $this->makeView('laravel-statistics::admin.statistics.detail',['res'=>$res]);
    }

    public function del(Request $request)
    {
        $query = $request->query();
        $redirect = $query?$this->index_url.'?'.http_build_query($query):$this->index_url;
        $post = $request->input('delete');
        if(!empty($post)){
            Statistics::whereIn('id',$post)->delete();
            throw new ApiException(['code'=>0,'msg'=>'操作成功','data'=>['redirect'=>$redirect]]);
        }
    }


}
