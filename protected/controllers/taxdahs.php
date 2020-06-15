<?php
//企业数据
class TaxdahsController extends TaxHandle
{
    public function init() {
        parent::init();
        $this->entity=new FinancialdocsEntity();
    }

     //列表信息
    public function index() {
    	 $this->reassign();
        $ids=ManagerEnterpriseService::getManagerEnterprise(
            $this->manager["id"],
            $this->manager["is_admin"],
            $this->manager["role_id"]
        );
        if ($ids) Req::args("in__etp_id",$ids);
        $data=$this->entity->query(Req::args());
        $this->assign("data",$data);
        $this->redirect();
    }

}
