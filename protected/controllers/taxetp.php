<?php

class TaxetpController extends TaxBase
{
    public $layout='taxbureau';

    public function init() {
        parent::init();
        $this->entity=new EnterpriseEntity();
    }

    public function index() {
        $ids=ManagerEnterpriseService::getManagerEnterprise(
            $this->manager["id"],
            $this->manager["is_admin"],
            $this->manager["role_id"]
        );
        if ($ids) Req::args("in__id",$ids);
        parent::index();
    }

    public function save()  {
        $this->checkFormToken();
        $post=Req::post();
        $id=Filter::int(Req::post("id"));
        $extend=array();
        $valid=$this->entity->saveValidator($extend,$errmsg);
        if ($valid) {
            $data=array_merge($post,$extend);
            $success=$this->entity->save($data);
            if ($success) {
                if (!$id) {
                    EnterpriseService::createAddress($success);
                    if (!$this->manager["is_admin"]) {
                        $med=array("manager_id"=>$this->manager["id"],"etp_id"=>$success);
                        (new ManagerEnterpriseEntity())->save($med);
                    }
                }
                echo "<script>parent.close_dialog(true);</script>";
            } else {
                $data["errmsg"]="保存失败，请重新再试！";
                $this->redirect("edit",true,$data);
            }
        } else {
            $post["errmsg"]=$errmsg;
            $this->redirect("edit",true,$post);
        }
    }

    //删除记录
    public function delete() {
        $info=["status"=>"fail","msg"=>"对不起，你无权记此录删！"];
        echo JSON::encode($info);
    }

    //修改记录状态
    public function status() {
        $info=["status"=>"fail","msg"=>"对不起，状态修改失败！"];
        echo JSON::encode($info);
    }

    //企业信息
    public function info() {
        $this->layout="blank";
        $id=Filter::int(Req::args("id"));
        $entity=new EnterpriseEntity();
        $enterprise=$entity->find($id);
        $infoEntity=new EnterpriseInfoEntity();
        $info=$infoEntity->findWhere("etp_id=$id");
        $data=array_merge($enterprise,$info);
        
        $adata=(new ShareholderEntity())->query(array("etp_id"=>$id));
        $this->assign("adata",$adata);

        $this->redirect("info",false,$data);
    }
    
    //风控分析
    public  function analysis() {
        $this->layout="blank";
        $id=Req::args("id");
        $enterprise=(new EnterpriseEntity())->find($id);
        $titles='["01月","02月","03月","04月","05月","06月","07月","08月","09月"]';
        if ($id==1) {
            $data1="[32.99,0,0,3.35,0,-45.44,54.28,0,0]";
            $data2="[32.99,0,0,3.35,0,-45.44,57.28,0,0]";
            $data3="[4.49,1.47,0,0,0,0,0,0,0]";
            $data4="[22.25,68.17,0,0,0,0,0,0,0]";
        } else {
            $data1="[0,0,0,0,0,0,0,0,0]";
            $data2="[0,0,0,0,0,0,0,0,0]";
            $data3="[0,0,0,0,0,0,0,0,0]";
            $data4="[0,0,0,0,0,0,0,0,0]";
        }

        $this->assign("etpname",$enterprise["full_name"]);

        $this->assign("titles",$titles);
        $this->assign("data1",$data1);
        $this->assign("data2",$data2);
        $this->assign("data3",$data3);
        $this->assign("data4",$data4);
        $this->redirect();
    }


   
  
}
