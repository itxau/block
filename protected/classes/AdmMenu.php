<?php
//系统菜单类
class AdmMenu
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
            '/admmagr/index'=> array(
                'name'=>'用户管理','parent'=>'system','icon'=>'fa-folder'
            ),
            '/admrole/index'=> array(
                'name'=>'角色管理','parent'=>'system','icon'=>'fa-unlock-alt'
            ),
            '/merchant/index'=> array(
                'name'=>'商户列表','parent'=>'merchant','icon'=>'fa-bars'
            ),
            '/merindustry/index'=> array(
                'name'=>'商户行业','parent'=>'merchant','icon'=>'fa-folder'
            ),
        );
        //分组菜单
        $subMenu = array(
            'merchant' =>array('name'=>'商户中心','parent'=>'system','icon'=>'fa-university','url'=>''),
            'enterprise' =>array('name'=>'企业中心','parent'=>'system','icon'=>'fa-users','url'=>''),
            'data' =>array('name'=>'数据中心','parent'=>'system','icon'=>'fa-database','url'=>''),
            'security' =>array('name'=>'安全中心','parent'=>'system','icon'=>'fa-shield','url'=>''),
            'system' =>array('name'=>'系统管理','parent'=>'system','icon'=>'fa-gears','url'=>''),
        );

        //主菜单
        $menu =array(
            //'light1' => "light-grey-hr mb-10",
            //'system' => array('link'=>'/admin/index','name'=>'system','icon'=>''),
           
            'system'=>array('link'=>'/admin/index','name'=>'系统设置'),
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
       
        if(isset($manager['role_id']) && !$manager['is_admin']){ 
            $role = (new RoleEntity())->find($manager['role_id']);
            if(isset($role['rights']))
                $rights = $role['rights'];
            else
                $rights = '';
            if(is_array($nodes)){
                $subMenuKey = array();
                foreach ($nodes as $key => $value) {
                    $_key = trim(strtr($key,'/','@'),'@');
                    if (stripos($rights, $_key)===false)
                        unset($nodes[$key]);
                    else{
                        if(!isset($subMenuKey[$value['parent']])) {
                            $subMenuKey[$value['parent']] = $key;
                        }
                    }
                }
                $menuKey = array();
                foreach ($subMenu as $key => $value) {
                    if(isset($subMenuKey[$key])){
                        $menuKey[$value['parent']] = $key;
                    }elseif(isset($value['url']) && $value['url']){
                        $_key = trim(strtr($value['url'],'/','@'),'@');
                        if(("admin@index"!=$_key) && (stripos($rights, $_key)===false)) {
                            unset($subMenu[$key]);
                        }
                    }elseif($value['name']!="hr"){
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
       
        //var_dump($nodes,$subMenu,$menu);exit;
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
        //print_r(expression)
        $this->link_key = '/'.(Req::get('con')==null?strtolower(Core::app()->defaultController):Req::get('con')).'/'.(Req::get('act')==null?Core::app()->getController()->defaultAction:Req::get('act'));
    }
    public function current_menu($key=null)
    {
        $key = $this->link_key;
        if(isset($this->nodes[$key]))
        {
            $subMenu = $this->nodes[$key]['parent'];
            $menu = $this->subMenu[$subMenu]['parent'];
            return array('menu'=>$menu,'subMenu'=>$subMenu);
        }
        return null;
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
