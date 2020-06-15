<?php
//控制器扩展
class ControllerExt implements Extension
{
    public function before($obj=null)
    {
        $config = Config::getInstance();
        $obj->assign('site_name',$config->get('site_name'));
        $obj->assign('site_full_name',$config->get('site_full_name'));
        $obj->assign('site_gov_name',$config->get('site_gov_name'));
        $obj->assign('site_logo_min',$config->get('site_logo_min'));
        $obj->assign('site_logo',$config->get('site_logo'));
    }
    public function after($obj=null)
    {
    }
}
