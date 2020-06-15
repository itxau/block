<?php

class gGvfidoController extends GovBase
{
    public function init() {
        parent::init();
        $this->entity=new FinancialdocsEntity();
    }

   
    public function blcup() {
        $this->layout="blank";
        $this->redirect();
    }

    public function blcup_act() {
        $address_id=Constants::GOV_ADDR_ID;

        $document_id=DocumentService::upload($address_id,"",$errmsg);
        if (!$document_id) {
            Req::post("errmsg",$errmsg);
            $this->redirect("blcup",true,Req::post());
            die();
        }
    
        $ext=array(
            "etp_id"=>$this->government["id"],
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
