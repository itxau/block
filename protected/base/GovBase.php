<?php
/**
 * 管理基类，负责管理控制器的权限、菜单等
 *
 * @author sun xian gen
 */
class GovBase extends BaseExt
{
	public $layout='government';
	public $govuser;
    public $gov_id=0;
    public $safebox;
	
    public function init() {
        $menu = new SystemMenu();
        $this->assign('systemMenu',$menu->getMenus());
        $currentUrl=$this->currentUrl();
        $currentNode=$menu->currentNode($currentUrl);
        $this->assign("currentUrl",$currentUrl);
        $this->assign("currentNode",$currentNode);
        if(isset($currentNode["current"]["name"])) {
            $this->assign('admin_title',$currentNode["current"]["name"]);
        }
        $this->safebox  = Safebox::getInstance();
        $this->govuser=$this->safebox->get('govuser');
        if ($this->govuser) {
            $this->assign('govuser',$this->govuser);
            $this->gov_id=$this->govuser["gov_id"];
        }
    }

    //列表信息
    public function index() {
        Req::args("gov_id",$this->gov_id);
        parent::index();
    }

    //修改记录
    public function edit()  {
        $this->layout="blank";
        $id=Filter::int(Req::args("id"));
        $data=array();
        if ($id) {
            $data=$this->entity->findWhere("(id=$id) and (gov_id=$this->gov_id)");
        }
        $this->redirect("edit",false,$data);
    }

    //记录详情
    public function info() {
        $this->layout="blank";
        $id=Filter::int(Req::args("id"));
        $data=array();
        if ($id) {
            $data=$this->entity->findWhere("(id=$id) and (gov_id=$this->gov_id)");
        }
        $this->redirect("info",false,$data);
    }

    public function delete() {
        $ids=Req::args("ids");
        $valid=$this->entity->deleteBeforeGov($this->govuser['gov_id'],$ids);
        if (!$valid) {
            $info=["status"=>"fail","msg"=>"你无权删除所选记录！"];
            echo JSON::encode($info);
            die();
        }  else {
            parent::delete();
        }
    }

    //修改记录状态
    public function status() {
        $ids = Req::post("ids");
        $valid=$this->entity->deleteBeforeGov($this->govuser['gov_id'],$ids);
        if (!$valid) {
            $info=["status"=>"fail","msg"=>"你无权修改所选记录！"];
            echo JSON::encode($info);
            die();
        }  else {
            parent::status();
        }
    }


    public function save()  {
        Req::post("gov_id",$this->gov_id);
        parent::save();
    }

    public function checkRight($actionId)  {
        
        if(in_array($actionId,Constants::NOT_CHECK_RIGHT)) return true;
        
        $code = strtolower($this->id).'@'.$actionId;

        $this->safebox = Safebox::getInstance();
        $govuser = $this->safebox->get('govuser');
        if (!$govuser || !$govuser['face_checked']) {
            $this->redirect("login");
            die();
        }
        if($govuser['is_admin']) return true;

        //不需要权限控制
        $notRights=array(
            "government@index","government@info",
            "government@password","government@password_act",
            "government@headpic","government@headpic_save",
            "government@clear","government@clear_act",
            "govradmt@index"
        );

      
        if(in_array($code,$notRights)) return true;
        
        if(isset($govuser['role_id'])) {
            $result = (new GovRoleEntity())->find($govuser['role_id']);
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
