<?php

class GovroleController extends GovBase
{
    public function init() {
        parent::init();
        $this->entity=new GovRoleEntity();
    }

    public function edit()  {
    	$this->layout="blank";
    	$module=new GovModuleEntity();
        $modules=GovRoleService::getModules($this->gov_id);

        $role_rights="";
        $id=Filter::int(Req::args("id"));
        $data=array();
        if ($id) {
            $data=$this->entity->findWhere("id=$id and gov_id=$this->gov_id");
            if ($data) $role_rights = $data['rights'];
        }
        $this->assign("role_rights", $role_rights);
        $this->assign("modules",$modules);
        $this->redirect("edit",false,$data);
    }
 
   
}
