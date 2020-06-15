<?php

class EnterpriseController extends EtpBase
{
   
    public function index() {
        $entity=new FinancialdocsEntity();
        $data1=$entity->queryAll(
            array(
                "etp_id"=>$this->enterprise["id"],
                "doctype_id"=>1
            )
        );
        $num1=count($data1);
        $data2=$entity->queryAll(
            array(
                "etp_id"=>$this->enterprise["id"],
                "doctype_id"=>2
            )
        );
        $num2=count($data2);
        $data3=$entity->queryAll(
            array(
                "etp_id"=>$this->enterprise["id"],
                "doctype_id"=>3
            )
        );
        $num3=count($data3);

        $data4=$entity->queryAll(
            array(
                "etp_id"=>$this->enterprise["id"],
                "doctype_id"=>4
            )
        );
        $num4=count($data4);

        $data=$entity->query(array("etp_id"=>$this->enterprise["id"]),"",10);
        //print_r($data);die();
        $this->assign("num1",$num1);
        $this->assign("num2",$num2);
        $this->assign("num3",$num3);
        $this->assign("num4",$num4);
        $this->assign("data",$data["data"]);
        $this->redirect();
    }

    //我的账号信息（+更新头像）
    public function account(){
        $this->layout="blank";
        $this->assign("headpicSrc",$this->enterprise["head_pic"]);
        $this->redirect("account",false,$this->enterprise);
    }

    //更新头像
    public function headpic_save() {
        $src=Filter::sql(Req::post("headpicSrc"));
        $success=EnterpriseService::updateHeadpic($this->enterprise["id"],$src);
        $enterprise=$this->enterprise;
        $enterprise["head_pic"]=$src;
        $this->safebox->set("enterprise",$enterprise);
        echo "<script>parent.close_dialog(false);</script>";
    }

    public function announces() {
        $this->reassign();
        $entity=new AnnounceEntity();
        $data=$entity->query(Req::post());
        $this->assign("data",$data);
        $this->redirect();
    }

    //企业信息
    public function info() {
        $infoEntity=new EnterpriseInfoEntity();
        $info=$infoEntity->findWhere("etp_id=".$this->enterprise["id"]);
        $data=array_merge($this->enterprise,$info);
        
        $adata=(new ShareholderEntity())->query(array("etp_id"=>$this->enterprise["id"]));
        $this->assign("adata",$adata);

        $this->redirect("info",false,$data);
    }

    //获得股东列表
    public function getgudong() {
        $data=(new ShareholderEntity())->queryAll(array("etp_id"=>$this->enterprise["id"]));

        echo JSON::encode(["status"=>"success","data"=>$data]);
        die();
    }

    //保存股东信息
    public function gudong_save() {
        $entity=new ShareholderEntity();
        Req::post("etp_id",$this->enterprise["id"]);
        $entity->save(Req::post());
        echo JSON::encode(["status"=>"success"]);
        die();
    }

    public function gudong_dele() {
        $id=Filter::int(Req::post("id"));
        $entity=new ShareholderEntity();
        $where="id=$id and etp_id=".$this->enterprise['id'];
        $success=$entity->deleteWhere($where);
        if ($success)
            echo JSON::encode(["status"=>"success"]);
        else
            echo JSON::encode(["status"=>"fail","msg"=>$where]);
        die();
    }

    //企业信息保存
    public function info_act() {
        EnterpriseService::saveinfo($this->enterprise["id"]);
        $enterprise=(new EnterpriseEntity())->find($this->enterprise["id"]);
        $enterprise["face_checked"]=1;
        $this->safebox->set("enterprise",$enterprise);
        $this->redirect("info");
    }

    //企业账号编码生成
    public function privatekeybycode() {
        $verify=MobileCodeService::verify(Req::args("mobile"),Req::args("code"),$errmsg);
        if ($verify) {
            $entity=new PrivatekeyEntity();
            $addr=$entity->findWhere("address_id=".$this->enterprise['address_id']);
            if ($addr) 
                $info=["status"=>"success","msg"=>$addr['privatekey']];
            else
                $info=["status"=>"fail","msg"=>"密钥还没有生成！"];
        } else {
            $info=["status"=>"fail","msg"=>$errmsg];
        }
        echo JSON::encode($info);
    }

    //生成企业账号
    public function etpkeygenerate() {
        $key=Common::generateAccount();
        echo JSON::encode(["status"=>"success","msg"=>$key]);
    }

    public function register() {
        $key=Common::generateAccount();
        $this->layout="toplayout";
        $this->assign("title","企业注册");
        $this->assign("account",$key);
        $this->redirect();
    }
   
    public function register_act() {
        $this->checkFormToken();
        $verify=MobileCodeService::verify(Req::post("mobile"),Req::post("code"),$errmsg);
        $post=Req::post();
        if (!$verify) {
            $post["errmsg"]=$errmsg;
            $this->redirect("register",true,$post);
            die();
        }
        $entity=new EnterpriseEntity();
        $extend=array();
        $valid=$entity->saveValidator($extend,$errmsg);
        if ($valid) {
            $data=array_merge($post,$extend);
            $id=$entity->save($data);
            if ($id) {
                EnterpriseService::createAddress($id);
                $this->redirect("/enterprise/login/account/".Req::post("account"));
            } else {
                $post["errmsg"]="注册失败！，请重新再试！";
                $this->redirect("register",true,$post);
            }
        } else {
            $post["errmsg"]=$errmsg;
            $this->redirect("register",true,$post);
        }
    }

    public function login() {
        $this->layout="login";
        $this->assign("account",Filter::sql(Req::args("a")));
        $this->redirect();
    }

    //登录验证
    public function login_act() {
        $code = $this->safebox->get($this->captchaKey);
        if ($code != strtolower(Req::post($this->captchaKey))) {
            echo JSON::encode(["status"=>"false","msg"=>"验证码错误！"]);
            die();
        } 
        $account=Filter::sql(Req::args('account'));
        $password=Filter::sql(Req::args('password'));
        $success=EnterpriseService::login($account,$password,$enterprise,$errmsg);
        if (!$success) {
            echo JSON::encode(["status"=>"fail","msg"=>$errmsg]);
            die();
        }
        $enterprise["face_checked"]=0;
        $request_id=FacequeueService::request(1,$enterprise["id"]);
        if ($request_id) {
            $this->safebox->set("enterprise",$enterprise);
            $info=["status"=>"success","msg"=>$request_id];
        } else {
            $info=["status"=>"fail","msg"=>"验证失败，请重新再试！"];
        }
        echo JSON::encode($info);die();

        /*
        if (!$enterprise["head_pic"]) { 
            echo JSON::encode(["status"=>"fail","msg"=>"你还没有上传头像，不允许登录"]);
            die();
        } 
        */



       

        //echo JSON::encode(["status"=>"success"]);
        //die();

        /*
        if (RUN_ENV=='DEV')
            $path="d:/face3.jpg";
        else
            $path=trim($enterprise['head_pic'],'/');
        if (!file_exists($path)) {
            echo JSON::encode(["status"=>"false","msg"=>"你还没有上传头像，不允许登录"]);
            die();
        }
        
        $userface=base64_encode(file_get_contents($path));
        $username=CHash::md5("etp_".$enterprise["id"]);
        echo JSON::encode(["status"=>"success","username"=>$username,"userface"=>$userface]);
        die();
        */

        
    }

    //检查人脸验证请求状态
    public function checkface() {
        $id=Filter::int(Req::args("id"));
        $queue=FacequeueService::check($id,$this->enterprise["id"]);
        if (!$queue) {
            $info=array("status"=>"fail","msg"=>"登录请求不存在","next"=>0);
            echo JSON::encode($info);die();
        } 

        if (strtotime($queue["expired_time"])<time()) {
            $info=array("status"=>"fail","msg"=>"登录请求已过期","next"=>0);
            echo JSON::encode($info);die();
        }
        if ($queue["status"]==1) {
            $info=array("status"=>"fail","msg"=>"登录请求未响应","next"=>1);
            echo JSON::encode($info);die();
        }  
        if ($queue["status"]==3) {
            $info=array("status"=>"fail","msg"=>"登录请求被拒绝","next"=>0);
            echo JSON::encode($info);die();
        }  
        if ($queue["status"]==2) {
            $enterprise=$this->safebox->get("enterprise");
            $enterprise["face_checked"]=1;
            $this->safebox->set("enterprise",$enterprise);
            $info=array("status"=>"success","msg"=>"登录请求已同意","next"=>0);
            echo JSON::encode($info);die();
        }
        $info=array("status"=>"fail","msg"=>"未知错误","next"=>0);
        echo JSON::encode($info);
    }

    public function facelogin_back() {
        $enterprise=$this->safebox->get("enterprise");
        $username=Filter::sql(Req::post('username'));
        $name=CHash::md5("etp_".$enterprise['id']);
        if ($username==$name) {
            $enterprise["face_checked"]=1;
            $this->safebox->set("enterprise",$enterprise);
            echo JSON::encode(["status"=>"success"]);
        } else {
            echo JSON::encode(["status"=>"false"]);
        }
        die();
    }

    public function password_act() {
        $this->layout="blank";
        $oldpasswd=Filter::sql(Req::post("oldpasswd"));
        $newpasswd=Filter::sql(Req::post("newpasswd"));
        $repasswd=Filter::sql(Req::post("repasswd"));
        $success=EnterpriseService::changeMPwd($this->enterprise["id"],$oldpasswd,$newpasswd,$repasswd,$errmsg);
        if ($success) {
            echo "<script>parent.close_pwddialog();</script>";
        } else {
            Req::post("errmsg",$errmsg);
            $this->redirect("password",true,Req::post());
        }
    }

    public function forgotpwd(){
        $this->layout="blank";
        $this->redirect();
    }

    //密码重置
    public function resetpwd() {
        $this->layout="blank";
        $mobile=Filter::sql(Req::post("mobile"));
        $etp=(new EnterpriseEntity())->findWhere("mobile='$mobile'");
        if (!$etp) {
            Req::post("errmsg","没有找到注册的企业信息！");
            $this->redirect("forgotpwd",true,Req::post());
        } else {
            $this->redirect("resetpwd",false,$etp);
        }
    }
    //密码重置
    public function resetpwd_act() {
        $id=Filter::int(Req::post("id"));
        $etp=(new EnterpriseEntity())->find($id);
        if (!$etp) {
            Req::post("errmsg","信息错误，无法重置密码！");
            $this->redirect("resetpwd",true,Req::post());
        } else {
            $password=Filter::sql(Req::post("password"));
            $repassword=Filter::sql(Req::post("repassword"));
            $success=EnterpriseService::changeUPwd($id,$password,$repassword,$errmsg);
            if ($success) {
                $this->redirect("reset",true,$etp);
            } else {
                Req::post("errmsg",$errmsg);
                $this->redirect("resetpwd",true,Req::post());
            }
        }
    }

    //密码重置成功
    public function reset(){
        $this->layout="blank";
        $this->redirect();
    }

    public function logout()  {
        $this->safebox->clearAll();
        $this->redirect('/enterprise/login');
    }
}
