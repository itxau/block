<?php
//系统菜单类
class Menu
{
    private $nodes;
    private $subMenu;
    private $menu;

    private $_menu;
    private $_subMenu;
    private $link_key;

    private $menus = array();


    public function __construct()
    {
        $nodes= array(
            '/merchant/index'=> array(
                'name'=>'商户信息','parent'=>'merchant','icon'=>'fa-user'
            ),
            '/merchannel/index'=> array(
                'name'=>'通道匹配','parent'=>'merchant','icon'=>'fa-exchange'
            ),
             '/help/index'=>array(
                'name'=>'API文档','parent'=>'merchant','icon'=>'fa-book'
            ),
            '/manager/index'=> array(
                'name'=>'用户管理','parent'=>'system','icon'=>'fa-key'
            ),
            '/role/index'=> array(
                'name'=>'角色管理','parent'=>'system','icon'=>''
            ),
            '/admin/clear'=>array(
                'name'=>'缓存清楚','parent'=>'system','icon'=>''
            ),
        );
        
        //分组菜单
        $subMenu = array(
            'admin'  =>array('name'=>'仪表板',  'parent'=>'system','icon'=>'fa-area-chart',
                'url'=>'/admin/index'),
            'merchant' =>array('name'=>'商户管理','parent'=>'system','icon'=>'fa-folder','url'=>''),
            'channel' =>array('name'=>'通道管理','parent'=>'system','icon'=>'fa-cube','url'=>'/channel/index'),
            'orders' =>array('name'=>'订单记录','parent'=>'system','icon'=>'fa-shopping-bag','url'=>'/order/index'),
            'finance'=>array('name'=>'财务流水','parent'=>'system','icon'=>'fa-calendar',
                'url'=>'/finance/index'),
            'system' =>array('name'=>'系统管理','parent'=>'system','icon'=>'fa-gears','url'=>''),
        );

        //主菜单
        $menu =array(
            //'light1' => "light-grey-hr mb-10",
            'system' => array('name'=>'system','icon'=>''),
        );

        $this->menus = array('nodes'=>$nodes,'subMenu'=>$subMenu,'menu'=>$menu);

        $this->init_menu();
    }

    public function init_menu(){
        $nodes = $this->menus['nodes'];
        $subMenu = $this->menus['subMenu'];
        $menu = $this->menus['menu'];
        $safebox = Safebox::getInstance();
        $manager = $safebox->get('manager');
        if(isset($manager['roles']) && !$manager['is_admin']){
            $result = (new RoleEntity())->findById($manager['roles']);
            if(isset($result['rights']))
                $rights = $result['rights'];
            else
                $rights = '';
            if(is_array($nodes)){
                $subMenuKey = array();
                foreach ($nodes as $key => $value) {
                    $_key = trim(strtr($key,'/','@'),'@');
                    if(stripos($rights, $_key)===false) 
                        unset($nodes[$key]);
                    else{
                        if(!isset($subMenuKey[$value['parent']]))
                            $subMenuKey[$value['parent']] = $key;
                        else{
                            if(stristr($key,'_list')) {
                                $subMenuKey[$value['parent']] = $key;
                            }
                        }
                    }
                }
                $menuKey = array();
                foreach ($subMenu as $key => $value) {
                    if(isset($subMenuKey[$key])){
                        $menuKey[$value['parent']] = $key;
                    }elseif(isset($value['url'])){
                        $_key = trim(strtr($value['url'],'/','@'),'@');
                        if(stripos($rights, $_key)===false) {
                            unset($subMenu[$key]);
                        }
                    }else{
                        unset($subMenu[$key]);
                    }
                }
                foreach ($menu as $key => $value) {
                    if(!isset($menuKey[$key]))
                        unset($menu[$key]);
                    else{
                        $menu[$key]['link'] = $subMenuKey[$menuKey[$key]];
                    }
                }
            }
        }
        //var_dump($subMenuKey,$menuKey,$menu);exit;
        if(is_array($nodes))
            $this->nodes = $nodes;
        else 
            $this->nodes = array();

        if(is_array($subMenu))
            $this->subMenu = $subMenu;
        else 
            $this->subMenu = array();

        if(is_array($menu))
            $this->menu = $menu;
        else 
            $this->menu = array();

        $this->_subMenu =  array();
        $this->_menu = array();

        foreach($this->nodes as $key => $nodes){
            $this->_subMenu[$nodes['parent']][] = array('link'=>$key,'name'=>$nodes['name'],'icon'=>$nodes['icon']);
        }
        foreach($this->subMenu as $key => $subMenu){
            $this->_menu[$subMenu['parent']][] = array('link'=>$key,'name'=>$subMenu['name'],'icon'=>$subMenu['icon'],'url'=>$subMenu['url']);
        }
        $this->link_key = '/'.(Req::get('con')==null?strtolower(Core::app()->defaultController):Req::get('con')).'/'.(Req::get('act')==null?Core::app()->getController()->defaultAction:Req::get('act'));
    }

    public function getMenus(){
        return $this->menus;
    }

    public function setMenus($menus){
        $this->menus = $menus;
    }

    public function currentMenu() {
        $link = $this->link_key;
        if(isset($this->nodes[$link])) {
            $subKey = $this->nodes[$link]['parent'];
            $mainKey = $this->subMenu[$subKey]['parent'];
            return array(
                'mainKey'=> $mainKey,
                'subKey' => $subKey,
                'subName' => $this->subMenu[$subKey]['name'],
                'nodeKey'=> $link,
                'title'   => $this->nodes[$link]["name"],
                'link'    => $link
            );
        } else {
            foreach ($this->subMenu as $key => $sub) {
                if($sub['url']==$link) {
                    return array(
                        'mainKey'=> $sub['parent'],
                        'subKey' => $key,
                        'subName' =>'',
                        'nodeKey'=> "",
                        'title'  => $sub["name"],
                        'link'   => $link
                    );
                }
            }
        }
        return null;
    }
    
    public function getMenu()  {
        return isset($this->menu)?$this->menu:array();
    }

    public function getSubMenu($key)  {
        return isset($this->_menu[$key])?$this->_menu[$key]:array();
    }

    public function getNodes($key) {
        return isset($this->_subMenu[$key])?$this->_subMenu[$key]:array();
    }

    public function currentNode()  {
        $key = $this->link_key;
        return isset($this->nodes[$key])?$this->nodes[$key]:array();
    }

    public function setLink($link_key){
        $this->link_key = $link_key;
    }

    public function getLink() {
        return $this->link_key;
    }
}
