<?php

namespace Aphly\LaravelStatistics\Models;

use Aphly\Laravel\Models\Manager;
use Aphly\Laravel\Models\Menu;
use Aphly\Laravel\Models\Module as Module_base;
use Illuminate\Support\Facades\DB;

class Module extends Module_base
{
    public $dir = __DIR__;
//$data[] =['id'=>51,'name' => '统计管理','route' =>'admin/statistics/index','pid'=>47,'uuid'=>$manager->uuid,'type'=>2,'module_id'=>$this->module_id,'sort'=>0];
//$data[] =['id'=>52,'name' => '统计详情','route' =>'admin/statistics/detail','pid'=>51,'uuid'=>$manager->uuid,'type'=>3,'module_id'=>$this->module_id,'sort'=>0];
//$data[] =['id'=>53,'name' => '统计删除','route' =>'admin/statistics/del','pid'=>51,'uuid'=>$manager->uuid,'type'=>3,'module_id'=>$this->module_id,'sort'=>0];
    public function install($module_id){
        parent::install($module_id);
        $manager = Manager::where('username','admin')->firstOrError();

        $menu = Menu::create(['name' => '统计管理','route' =>'','pid'=>0,'uuid'=>$manager->uuid,'type'=>1,'module_id'=>$module_id,'sort'=>10]);
        if($menu->id){
            $data=[];
            $data[] =['name' => 'Host管理','route' =>'statistics_admin/host/index','pid'=>$menu->id,'uuid'=>$manager->uuid,'type'=>2,'module_id'=>$module_id,'sort'=>0];
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
