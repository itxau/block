<?php
//角色管理
class AdmroleController extends AdmBase
{
    public function init() {
        parent::init();
        $this->entity=new RoleEntity();
    }

    public function edit()  {
    	$this->layout="blank";
        $modules=array(
        	"enterprise"=>"企业信息",
        	"government" =>"政务通",
        	"service"   =>"服务中心",
            "manager"    =>"系统管理",
        	"other"     =>"其他"
        );
        $role_rights="";
        $id=Filter::int(Req::args("id"));
        $data=array();
        if ($id) {
            $data=$this->entity->find($id);
            if ($data) $role_rights = $data['rights'];
        }
        $this->assign("rights", $role_rights);
        $this->assign("modules",$modules);
        $this->redirect("edit",false,$data);
    }
}
