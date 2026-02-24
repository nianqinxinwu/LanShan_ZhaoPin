<?php

namespace addons\zpwxsys;

use app\admin\controller\general\Config;
use app\common\library\Menu;
use app\common\model\Config as ConfigModel;
use think\Addons;

/**
 * 插件
 */
class Zpwxsys extends Addons
{




    /**
     * 插件安装方法
     * @return bool
     */
    public function install()
    {
        $config_file= ADDON_PATH ."zpwxsys" . DS.'config'.DS. "menu.php";
        if (is_file($config_file)) {
           $menu = include $config_file;
        }
        if($menu){
            Menu::create($menu);
        }
        return true;
    }

    /**
     * 插件卸载方法
     * @return bool
     */
    public function uninstall()
    {
        Menu::delete('zpwxsys');
        return true;
    }

    /**
     * 插件启用方法
     * @return bool
     */
    public function enable()
    {
        Menu::enable('zpwxsys');
    }

    /**
     * 插件禁用方法
     * @return bool
     */
    public function disable()
    {
        Menu::disable('zpwxsys');
    }

    /**
     * 插件升级方法
     * @return bool
     */
    public function upgrade()
    {
        //如果菜单有变更则升级菜单
       	$config_file= ADDON_PATH ."zpwxsys" . DS.'config'.DS. "menu.php";
        if (is_file($config_file)) {
           $menu = include $config_file;
        }
        if($menu){
            Menu::upgrade('zpwxsys', $menu);
        }
        return true;
        
    }
}
