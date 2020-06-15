<?php
/**
* 数据上链
**/
class GovdahsController extends GovBase
{
    public function init() {
        parent::init();
        $this->entity=new GovDatahistoryEntity();
    }

    public function blcup() {
        $this->layout="blank";
        $this->redirect();
    }

    public function blcup_act() {
        $address_id=$this->govuser["address_id"];
        if (!$address_id) {
            Req::post("errmsg","信息不完整！");
            $this->redirect("blcup",true,Req::post());
            die();
        }
        $title=Filter::sql(Req::post("title"));

        $document_id=DocumentService::upload($address_id,$title,$errmsg);
        if (!$document_id) {
            Req::post("errmsg",$errmsg);
            $this->redirect("blcup",true,Req::post());
            die();
        }
    
        $ext=array(
            "gov_id"=>$this->gov_id,
            "document_id"=>$document_id
        );
        $data=array_merge(Req::post(),$ext);
        $success=$this->entity->save($data);
        
        if ($success) {
            echo "<script>parent.close_dialog(true);</script>";
        } else {
            Req::post("errmsg","上链失败，请重新再试！");
            $this->redirect("blcup",true,Req::post());
        }
    }
}
