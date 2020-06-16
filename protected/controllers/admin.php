<?php

class AdminController extends AdmBase
{
    public $layout='admin';

    public function init() {
        parent::init();
    }

    public function senddoc() {
        $this->layout="blank";
        $this->redirect();
    }

    public function senddoc_act() {
        $address_id=7;
     
        $document_id=DocumentService::upload($address_id,"",$errmsg);
        if (!$document_id) {
            print_r($errmsg);die();
            Req::post("errmsg",$errmsg);
            $this->redirect("senddoc",true,Req::post());
            die();
        }

        $data=array(
            "etp_id"=>1,
            "document_id"=>$document_id,
            "message"=>'',
            "sender"=>1,
            "type"=>2
        );
        $entity=new MessageEntity();
        $entity->save($data);
        echo "<script>parent.close_dialog(true);</script>";
    }

    public function online(){
        $this->assign("etp_id",1);
        $this->redirect();
    }

    public function tzonline(){
        $this->assign("etp_id",54);
        $this->redirect();
    }

    public function getmsg() {
        $id=Req::args("id");
        $entity=new MessageEntity();
        $data=$entity->queryAll(array("etp_id"=>$id));
        $info=array("status"=>"success","msg"=>"","data"=>$data);
        echo JSON::encode($info);
    }

    public function messagesave() {
        $ept_id=Filter::int(Req::post("id"));
        $message=Filter::sql(Req::post("message"));
        $data=array(
            "etp_id"=>$ept_id,
            "document_id"=>1,
            "message"=>$message,
            "sender"=>1,
            "type"=>1
        );
        $entity=new MessageEntity();
        $entity->save($data);
        echo JSON::encode(["status"=>"success","msg"=>""]);
    }

    public function message(){
        $this->layout="blank";
        $id=Req::args("id");
        $this->assign("etp_id",$id);
        $this->redirect();
    }
  
    
    public function index() {   
       
        $this->assign("eptNum",1);

      
        $this->assign("docNum",2);

      
        $this->assign("prdNum",0);
       
        $this->assign("prdlist",[]);

      
        $this->assign("dclNum",1);
      
        $this->assign("dclist",[]);

        $this->redirect();
    }

    //登录
    public function login()   {
        $this->layout = 'login';
        
        $this->redirect();
    }

    //登录验证
    public function login_act() {
        $code = $this->safebox->get($this->captchaKey);
        if ($code != strtolower(Req::post($this->captchaKey))) {
            echo JSON::encode(["status"=>"false","msg"=>"验证码错误！"]);
            die();
        } 
        $ip=Chips::getIP();
        $name=Filter::sql(Req::post('name'));
        $password=Filter::sql(Req::post('password'));
        $success=ManagerService::login($name,$password,$ip,$manager,$errMsg);
        if (!$success) {
            echo JSON::encode(["status"=>"false","msg"=>$errMsg]);
            die();
        }
        $manager["face_checked"]=1;
        if($name=="admin3"){
            $manager["face_checked"]=1; 
        } 
        $this->safebox->set("manager",$manager);     
        if (!$manager["head_pic"]) { 
            echo JSON::encode(["status"=>"false","msg"=>"你还没有上传头像，不允许登录"]);
            die();
        } 
      
      
        //echo JSON::encode(["status"=>"success"]);
        //die();

        if (RUN_ENV=='DEV')
            $path="d:/face3.jpg";
        else
            $path=trim($manager['head_pic'],'/');
        if (!file_exists($path)) {
            echo JSON::encode(["status"=>"false","msg"=>"你还没有上传头像，不允许登录"]);
            die();
        }
        $userface=base64_encode(file_get_contents($path));
        $username=CHash::md5("tax_".$manager['id']);
        echo JSON::encode(["status"=>"success","username"=>$username,"userface"=>$userface]);
        die();
    }

    public function facelogin() {
        $manager=$this->safebox->get("manager");
        $username=Filter::sql(Req::post('username'));
        $name=CHash::md5("tax_".$manager['id']);
        if ($username==$name) {
            $manager["face_checked"]=1;
            $this->safebox->set("manager",$manager);
            echo JSON::encode(["status"=>"success"]);
        } else {
            echo JSON::encode(["status"=>"false"]);
        }
        die();
    }
   
    public function logout()  {
        $this->safebox = Safebox::getInstance();
        $this->safebox->clearAll();
        $this->redirect('/admin/login');
    }

    public function password_act() {
        $this->layout="blank";
        $oldpasswd=Filter::sql(Req::post("oldpasswd"));
        $newpasswd=Filter::sql(Req::post("newpasswd"));
        $repasswd=Filter::sql(Req::post("repasswd"));
        $success=ManagerService::changeMPwd($this->manager["id"],$oldpasswd,$newpasswd,$repasswd,$errmsg);
        if ($success) {
            echo "<script>parent.close_pwddialog();</script>";
        } else {
            Req::post("errmsg",$errmsg);
            $this->redirect("password",true,Req::post());
        }
    }

    //记录详情
    public function info() {
        $this->layout="blank";
        $id=$this->manager['id'];
        $data=array();
        if ($id) {
            $data=(new ManagerEntity())->queryFind(["id"=>$id]);
        }
        $this->redirect("info",false,$data);
    }

    public function headpic() {
        $this->layout="blank";
        $this->assign("headpicSrc",$this->manager["head_pic"]);
        $this->redirect();
    }

    public function headpic_save() {
        $src=Filter::sql(Req::post("headpicSrc"));
        $success=ManagerService::updateHeadpic($this->manager["id"],$src);
        $manager=$this->manager;
        $manager["head_pic"]=$src;
        $this->safebox->set("manager",$manager);
        echo "<script>parent.close_dialog(false);</script>";
    }
 
}
