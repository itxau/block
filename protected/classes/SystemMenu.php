<?php
//系统菜单类
class SystemMenu
{
    private $systemMenu=array();
    private $rights="";
    private $user=null;
    private $gov_id=0;
    private $safebox=null;

    private $breadcrumb=array();

    public function __construct()  {
        $this->safebox = Safebox::getInstance();
        //当前用户
        $user = $this->safebox->get('govuser');
        if (!$user) return;
        $this->user=$user;
        $this->gov_id=$user["gov_id"];

        //用户权限
        $right_key="userRights_".$user['id'];
        $this->rights=$this->safebox->get($right_key);
        if (!$this->rights) {
            if ($user['is_admin'])
                $this->rights="all";
            elseif(isset($user['role_id'])) {
                $role=(new GovRoleEntity())->find($user['role_id']);
                if(isset($role['rights']))  $this->rights = strtolower($role['rights']);
            }else{
                $this->rights="norights";
            }
            $this->safebox->set($right_key,$this->rights);
        }

        //系统菜单
        $this->systemMenu=$this->safebox->get('systemMenu');
        if (!$this->systemMenu) {
            $this->systemMenu=$this->getSubmenu(0);
            $this->safebox->set('systemMenu',$this->systemMenu);
        }

    }

    //当前用户的菜单项
    public function getMenus() {
        if (!$this->user) return array();
        if ($this->user['is_admin'])
            return $this->systemMenu;
        else {
            $menu=$this->safebox->get("userMenu_".$this->user['id']);
            
            if (!$menu) {
                $menu=$this->authorityRighs($this->systemMenu);
                $menu=$this->checkRighs($menu);
            }

            $this->safebox->set("userMenu_".$this->user['id'],$menu);
            return $menu;
        }
    }

    //当前页面的面包菜单
    public function currentNode($url) {
        $breadcrumb=array(
            "current"=>array("id"=>0,"name"=>"","url"=>"","parent_id"=>0),
            "parent" =>array("id"=>0,"name"=>"","url"=>"","parent_id"=>0)
        );

        $model=new Model("gov_menu");
        $where="(gov_id=$this->gov_id) and (url='$url') and (status=1)";
        $node=$model->fields("id,name,url,parent_id")->where($where)->find();
        if ($node) {
            $breadcrumb['current']=$node;
            if ($node["parent_id"])  {
                $breadcrumb['parent']=$this->parentMenu($node["parent_id"]);
            }
        }

        return $breadcrumb;
    }

    //父节点
    private function parentMenu($parent_id) {
        $model=new Model("gov_menu");
        $where="(gov_id=$this->gov_id) and (id=$parent_id) and (status=1)";
        return $model->fields("id,name,url,parent_id")->where($where)->find();
        
    }

    private function authorityRighs($nodes) {
        foreach ($nodes as $key => $node) {
            if (!$node["have_son"]){
                if (!$node['default']) {
                    $url = strtolower(trim(strtr($node["url"],'/','@'),'@'));
                    if(stripos($this->rights, $url)===false) {
                        unset($nodes[$key]);
                    }
                }
            } elseif ($node['submenu']) {
                $nodes[$key]['submenu']=$this->authorityRighs($node['submenu']);
            } else {
                unset($nodes[$key]);
            }
        }
        return $nodes;
    }

    private function checkRighs($nodes) {
        foreach ($nodes as $key => $node) {
            if ($node["have_son"]){
                if(!$node['submenu']){
                    unset($nodes[$key]);
                } else {
                    $nodes[$key]['submenu']=$this->checkRighs($node['submenu']);
                }
            } 
        }
        return $nodes;
    }


    private function getSubmenu($parent_id) {
        $list=$this->getSublist($parent_id);
        $nodes=array();
        foreach ($list as $key => $node) {
            if ($node["have_son"]) {
                $node["submenu"]=$this->getSubmenu($node["id"]);
            }
            $nodes[]=$node;
       }
       return $nodes;
    }

    private function getSublist($parent_id){
        $model=new Model("gov_menu");
        return $model->where("(gov_id=$this->gov_id) and (parent_id=$parent_id) and (status=1)")->order("sort")->findAll();
    }

   
}
