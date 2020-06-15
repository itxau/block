<?php
//系统菜单类
class EtpMenu
{
    public function getMenus() {
        return array(
            '/enterprise/index'=>array(
                'title'=>'首页','icon'=>'fa-pie-chart','hidden'=>false
            ),
            '/enterprise/info'=>array(
                'title'=>'企业详情', 'icon'=>'fa-info','hidden'=>false
            ),
            '/etpdahs/index'=>array(
                'title'=>'企业数据', 'icon'=>'fa-database','hidden'=>false
            ),
            '/etpmeet/index'=>array(
                'title'=>'视频会议', 'icon'=>'fa-video-camera','hidden'=>false
            ),
            /*
            '/enterprise/online'=>array(
                'title'=>'税务在线', 'icon'=>'fa-comments','hidden'=>false
            ),
            */
            '/etphall/index'=>array(
                'title'=>'办税大厅', 'icon'=>'fa-building','hidden'=>false
            ),
            '/etpreco/index'=>array(
                'title'=>'我要备案', 'icon'=>'fa-clipboard','hidden'=>true
            ),
            '/etpdltx/index'=>array(
                'title'=>'我要申报', 'icon'=>'fa-clipboard','hidden'=>true
            ),
            '/etpinvo/index'=>array(
                'title'=>'我要购票', 'icon'=>'fa-clipboard','hidden'=>true
            ),
            '/etprftx/index'=>array(
                'title'=>'我要退税', 'icon'=>'fa-clipboard','hidden'=>true
            ),
            '/etpdstx/index'=>array(
                'title'=>'我要优惠', 'icon'=>'fa-clipboard','hidden'=>true
            ),
            '/etpanno/index'=>array(
                'title' => '公告通知', 'icon'=>'fa-commenting','hidden'=>false
            ),
            '/enterprise/logout'=>array(
                'title' => '安全退出', 'icon'=>'fa-power-off','hidden'=>false
            )
        );
    }
}
