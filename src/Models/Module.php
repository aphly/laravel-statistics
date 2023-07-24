<?php

namespace Aphly\LaravelStatistics\Models;

use Aphly\Laravel\Models\Manager;
use Aphly\Laravel\Models\Menu;
use Aphly\Laravel\Models\Module as Module_base;
use Illuminate\Support\Facades\DB;

class Module extends Module_base
{
    public $dir = __DIR__;

    public function install($module_id){
        parent::install($module_id);
        $path = storage_path('app/private/ipv4.sql');
        if(file_exists($path)){
            DB::unprepared(file_get_contents($path));
        }
        $manager = Manager::where('username','admin')->firstOrError();
        $menu = Menu::create(['name' => '统计','route' =>'','pid'=>0,'uuid'=>$manager->uuid,'type'=>1,'module_id'=>$module_id,'sort'=>10]);
        if($menu->id){
            $data=[];
            $data[] =['name' => '站点管理','route' =>'statistics_admin/host/index','pid'=>$menu->id,'uuid'=>$manager->uuid,'type'=>2,'module_id'=>$module_id,'sort'=>0];
            DB::table('admin_menu')->insert($data);
        }
        $menuData = Menu::where(['module_id'=>$module_id])->get();
        $data=[];
        foreach ($menuData as $val){
            $data[] =['role_id' => 1,'menu_id'=>$val->id];
        }
        DB::table('admin_role_menu')->insert($data);
        return 'install_ok';
    }

    public function uninstall($module_id){
        parent::uninstall($module_id);
        return 'uninstall_ok';
    }


}
