<?php
/**
 * description...
 *
 * @author:sun xian gen(baxgsun@163.com)
 * @package GovernmentController
 */
class GovernmentController extends GovBase
{
   

    public function index() {

        $entity=new GovDatahistoryEntity();
        $doc_num=$entity->count("gov_id=$this->gov_id");
        $this->assign("doc_num",$doc_num);

        $entity=new DatashareEntity();
        $datashare_num=$entity->count("from_id=$this->gov_id");
        $this->assign("datashare_num",$datashare_num);
        $datashare=$entity->queryAll(['from_id'=>$this->gov_id],"created_time",10);
        $this->assign("datashare",$datashare);

        $entity=new DiscountaxEntity();
        $distax_num=$entity->count("status<>1");
        $this->assign("distax_num",$distax_num);
        $distax=$entity->queryAll(['ne__status'=>1],"created_time",10);
        $this->assign("distax",$distax);

        $entity=new GovUserEntity();
        $user_num=$entity->count("gov_id=$this->gov_id");
        $this->assign("user_num",$user_num);

        $this->redirect();
    }
   

    public function logout()  {
        $this->safebox->clearAll();
        $this->redirect('/index/government');
    }

    public function password() {
        $this->layout="blank";
        $this->redirect();
    }

    public function password_act() {
        $this->layout="blank";
        $oldpasswd=Filter::sql(Req::post("oldpasswd"));
        $newpasswd=Filter::sql(Req::post("newpasswd"));
        $repasswd=Filter::sql(Req::post("repasswd"));
        $success=GovUserService::changeMPwd($this->govuser["id"],$oldpasswd,$newpasswd,$repasswd,$errmsg);
        if ($success) {
            echo "<script>parent.close_pwddialog();</script>";
        } else {
            Req::post("errmsg",$errmsg);
            $this->redirect("password",true,Req::post());
        }
    }

    public function headpic() {
        $this->layout="blank";
        $this->assign("headpicSrc",$this->govuser["head_pic"]);
        $this->redirect();
    }

    public function headpic_save() {
        $src=Filter::sql(Req::post("headpicSrc"));
        $success=GovUserService::updateHeadpic($this->govuser["id"],$src);
        $govuser=$this->govuser;
        $govuser["head_pic"]=$src;
        $this->safebox->set("govuser",$govuser);
        echo "<script>parent.close_dialog(false);</script>";
    }


    public function getmsg() {
        $entity=new MessageEntity();
        $data=$entity->queryAll(["etp_id"=>$this->government["id"]]);
        $info=array("status"=>"success","msg"=>"","data"=>$data);
        echo JSON::encode($info);
    }

    public function messagesave() {
        $id=$this->government["id"];
        $message=Req::post("message");
        $data=array(
            "etp_id"=>$id,
            "document_id"=>0,
            "message"=>$message,
            "sender"=>2,
            "type"=>1
        );
        $entity=new MessageEntity();
        $entity->save($data);
        echo JSON::encode(["status"=>"success","msg"=>""]);
    }

    public function senddoc() {
        $this->layout="blank";
        $this->redirect();
    }
    
    public function senddoc_act() {
        $address_id=$this->government["address_id"];
      
        $document_id=DocumentService::upload($address_id,"",$errmsg);
        if (!$document_id) {
            Req::post("errmsg",$errmsg);
            $this->redirect("senddoc",true,Req::post());
            die();
        }

        $data=array(
            "etp_id"=>$this->government["id"],
            "document_id"=>$document_id,
            "message"=>'',
            "sender"=>2,
            "type"=>2
        );
        $entity=new MessageEntity();
        $entity->save($data);
        echo "<script>parent.close_dialog(true);</script>";
    }

    public function online(){
        $this->assign("title","税政在线");
        $this->assign("etp_id",$this->government["id"]);
        $this->redirect();
    }
   
}
