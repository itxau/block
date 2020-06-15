<?php
//政务单位授权
class TaxgtgvController extends TaxBase
{
    public function init() {
        parent::init();
        $this->entity=new ManagerGovernmentEntity();
    }

    public function index() {
        $mid=Filter::int(Req::args("mid"));
        $did=Filter::int(Req::args("did"));
        if ($mid) {
            Req::args("manager_id",$mid);
            Req::args("mid",0);
        } else {
            Req::args("manager_id",0);
        }

        if ($did) {
            Req::args("eq__mgr_d_dept_id",$did);
            Req::args("did",0);
        } 

        parent::index();
    }

    public function grant() {
    	$this->layout="blank";
        $dept_id=Filter::int(Req::args("dept_id"));
        $manager_id=Filter::int(Req::args("manager_id"));
        $page=Filter::int(Req::args("page"));
        $data=array("data"=>array(),"html"=>"");
        if ($manager_id) {
        	$data=ManagerGovernmentService::getGrandedGovernment($manager_id,$page);

            if (!$dept_id) {
                $entity=new ManagerEntity();
                $mgr=$entity->find($manager_id);
                $dept_id=intval($mgr["dept_id"]);
            }
        } 
        $this->assign("data",$data);
        $this->assign("dept_id",$dept_id);
        $this->assign("manager_id",$manager_id);
        $this->redirect();
    }

    public function selectgov() {
        $this->layout="blank";
        $this->reassign();
        $dosubmit=Filter::sql(Req::args("dosubmit"));
        $manager_id=Filter::int(Req::args("manager_id"));
        $page=Filter::int(Req::args("page"));

        if ($dosubmit=="dosubmit") {
            $ids=Req::args("selectone");
            $manager_id=Filter::int(Req::args("manager_id"));
            ManagerGovernmentService::saveGrandedGovernment($manager_id,$ids);
            $this->redirect("/taxgtgv/grant/manager_id/$manager_id");
        } else {
            $data=ManagerGovernmentService::getUngrandGovernment($manager_id,$page);
            $this->assign("manager_id",$manager_id);
            $this->assign("data",$data);
            $this->redirect();
        }
    }
 
}
