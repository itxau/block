<?php
/**
 * 前海管理局
 *
 * @author:sun xian gen(baxgsun@163.com)
 * @package AdmbureauController
 */
class GovadmtController extends GovBase
{
    public function index() {
        $this->redirect("/government/index");
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
        $account=Filter::sql(Req::post('account'));
        if ($account!="GOV001") {
            echo JSON::encode(["status"=>"false","msg"=>"非法登录！"]);
            die();
        }
        $ip=Chips::getIP();
        $name=Filter::sql(Req::post('name'));
        $password=Filter::sql(Req::post('password'));
        $success=GovUserService::login($account,$name,$password,$ip,$govuser,$errMsg);
        if (!$success) {
            echo JSON::encode(["status"=>"false","msg"=>$errMsg]);
            die();
        }
        
      
        if (!$govuser["head_pic"]) { 
            echo JSON::encode(["status"=>"false","msg"=>"你还没有上传头像，不允许登录"]);
            die();
        } 
        

        $govuser["face_checked"]=1; 
        $this->safebox->set("govuser",$govuser);

        //echo JSON::encode(["status"=>"success"]);
        //die();

       
        if (RUN_ENV=='DEV')
            $path="d:/face3.jpg";
        else
            $path=trim($govuser['head_pic'],'/');
        if (!file_exists($path)) {
            echo JSON::encode(["status"=>"false","msg"=>"你还没有上传头像，不允许登录"]);
            die();
        }

        $userface=base64_encode(file_get_contents($path));
        $username=CHash::md5("gov_".$govuser["gov_id"]."_".$govuser['id']);
        echo JSON::encode(["status"=>"success","username"=>$username,"userface"=>$userface]);
        die();
       
    }

    public function facelogin() {
        $govuser=$this->safebox->get("govuser");
        $username=Filter::sql(Req::post('username'));
        $name=CHash::md5("gov_".$govuser["gov_id"]."_".$govuser['id']);
        if ($username==$name) {
            $govuser["face_checked"]=1;
            $this->safebox->set("govuser",$govuser);
            echo JSON::encode(["status"=>"success"]);
        } else {
            echo JSON::encode(["status"=>"false"]);
        }
        die();
    }

}
