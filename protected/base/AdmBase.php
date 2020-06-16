<?php
/**
 * 税务端管理基类，负责管理控制器的权限、菜单等
 *
 * @author sun xian gen
 */
class AdmBase extends BaseExt
{
	public $layout='admin';
	protected $manager;
    public $safebox;
    public $entity;
	public function init() {
        $menu = new AdmMenu();
        //print_r($menu->getSubMenu("system"));die();
        $this->assign('menu',$menu);
        //$this->assign('mainMenu',$menu->getSubMenu("system"));
        $currentMenu = $menu->currentMenu();
        $currentLine = $menu->getLink();
        $this->assign('currentMenu',$currentMenu);
        $this->assign('currentLine',$currentLine);
        if(isset($currentMenu['title'])) {
            $this->assign('admin_title',$currentMenu['title']);
        }
        $this->safebox = Safebox::getInstance();
        $this->manager = $this->safebox->get('manager');
        $this->assign('manager',$this->safebox->get('manager'));

        $con = '/'.(Req::get('con')==null?strtolower(Core::app()->defaultController):Req::get('con'));
        $act='/'.(Req::get('act')==null?Core::app()->getController()->defaultAction:Req::get('act'));
        $this->assign("con",$con);
        $this->assign("act",$act);
	}

   
    public function checkRight($actionId)  {
        
        if(in_array($actionId,Constants::NOT_CHECK_RIGHT)) return true;
        
        $code = strtolower($this->id).'@'.$actionId;

        $this->safebox = Safebox::getInstance();
        $manager = $this->safebox->get('manager');
        if (!$manager || !$manager['face_checked']) {
            $this->redirect("/admin/login");
            die();
        }
        
        if($manager['is_admin']) return true;

        //不需要权限控制
        $notRights=array(
            "admin@index","admin@info",
            "admin@password","admin@password_act",
            "admin@headpic","admin@headpic_save",
            "admin@clear","admin@clear_act"
        );
        if(in_array($code,$notRights)) return true;
        
        if(isset($manager['role_id'])) {
            $result = (new RoleEntity())->find($manager['role_id']);
            if(isset($result['rights']))
                $rights = $result['rights'];
            else
                $rights = '';
            if(stripos(strtolower($rights),$code)!==false){
                return true;
            }
            else
                return false;
        }
        else{
            return false;
        }
        return false;
    }
    
    public function noRight()   {
        if ($this->is_ajax_request()) {
            echo JSON::encode(array('status' => 'fail', 'msg' => '没有该项操作权限!'));
        } else {
            Core::Msg($this,'你无权访问该页面！',503);
        }
    }

    //下载文档
    public function downfile() {
        //记录ID
        $rid=Filter::int(Req::args("rid"));
        $obj=$this->entity->find($rid,"id");
        if (!$obj) {
            print_r("文档密钥信息不完整，无法下载！");die();
        }

        //文档ID
        $tid=Filter::sql(Req::args("tid"));
        $txid=Req::args("tid");
        $addid=Filter::int(Req::args("aid"));
        if (!$txid || !$addid) {
            print_r("文档密钥信息不完整，无法下载！");die();
        }

        //权限检查--begin

        //权限检查--end

        $entity=new PrivatekeyEntity();
        $priKey=$entity->findWhere("address_id=$addid");
        if (!$priKey || !$priKey["privatekey"]) {
            print_r("文档密钥不完整，无法下载！");die();
        }
        
        $success=BlcHelper::downFile($addid,$priKey["privatekey"],$txid);
        if (!$success) {
             print_r("文档密钥错误，无法下载！");die();
        }
    }

       
  

}
