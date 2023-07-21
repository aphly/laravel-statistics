<?php

namespace Aphly\LaravelStatistics\Controllers\Admin;

use Aphly\Laravel\Exceptions\ApiException;
use Aphly\Laravel\Models\Breadcrumb;

use Aphly\LaravelStatistics\Models\StatisticsHost;
use Illuminate\Http\Request;

class StatisticsHostController extends Controller
{
    public $index_url='/statistics_admin/host/index';

    private $currArr = ['name'=>'Host','key'=>'host'];

    public function index(Request $request)
    {
        $res['search']['name'] = $request->query('name','');
        $res['search']['string'] = http_build_query($request->query());
        $res['list'] = StatisticsHost::when($res['search'],
            function($query,$search) {
                if($search['name']!==''){
                    $query->where('name', 'like', '%'.$search['name'].'%');
                }
            })
            ->orderBy('id','desc')
            ->Paginate(config('admin.perPage'))->withQueryString();
        $res['breadcrumb'] = Breadcrumb::render([
            ['name'=>$this->currArr['name'].'管理','href'=>$this->index_url]
        ]);
        return $this->makeView('laravel-statistics::admin.host.index',['res'=>$res]);
    }

    public function form(Request $request)
    {
        $res['info'] = StatisticsHost::where('id',$request->query('id',0))->firstOrNew();
        if($res['info']->id){
            $res['breadcrumb'] = Breadcrumb::render([
                ['name'=>$this->currArr['name'].'管理','href'=>$this->index_url],
                ['name'=>'编辑','href'=>'/statistics_admin/'.$this->currArr['key'].'/form?id='.$res['info']->id]
            ]);
        }else{
            $res['breadcrumb'] = Breadcrumb::render([
                ['name'=>$this->currArr['name'].'管理','href'=>$this->index_url],
                ['name'=>'新增','href'=>'/statistics_admin/'.$this->currArr['key'].'/form']
            ]);
        }
        return $this->makeView('laravel-statistics::admin.host.form',['res'=>$res]);
    }

    public function save(Request $request){
        $input = $request->all();
        if(empty($input['appid'])){
            $input['appid'] = date('Ymd') . str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);
        }
        StatisticsHost::updateOrCreate(['id'=>$request->query('id',0)],$input);
        throw new ApiException(['code'=>0,'msg'=>'success','data'=>['redirect'=>$this->index_url]]);
    }

    public function del(Request $request)
    {
        $query = $request->query();
        $redirect = $query?$this->index_url.'?'.http_build_query($query):$this->index_url;
        $post = $request->input('delete');
        if(!empty($post)){
            StatisticsHost::whereIn('id',$post)->delete();
            throw new ApiException(['code'=>0,'msg'=>'操作成功','data'=>['redirect'=>$redirect]]);
        }
    }

}
