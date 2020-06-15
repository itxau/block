<?php
//政务部门管理
class TaxgovController extends TaxBase
{
    public function init() {
        parent::init();
        $this->entity=new GovernmentEntity();
    }

    public function index() {
        $ids=ManagerGovernmentService::getManagerGovernment(
            $this->manager["id"],
            $this->manager["is_admin"],
            $this->manager["role_id"]
        );
        if ($ids) Req::args("in__id",$ids);
    	Req::args("show",1);
    	parent::index();
    }

    public function edit() {
    	$this->layout="blank";
        $id=Filter::int(Req::args("id"));
        $data=array();
        if ($id) {
            $data=$this->entity->find($id);
            $entity=new GovUserEntity();
            $user=$entity->findWhere("gov_id=$id and is_admin=1");
            if ($user) {
            	$data["user_id"]=$user["id"];
            	$data["user_name"]=$user["name"];
            	$data["head_pic"]=$user["head_pic"];
            }
        }
        $this->redirect("edit",false,$data);
    }

    public function save()  {
        $this->checkFormToken();
        $post=Req::post();
        $extend=array();
        $valid=$this->entity->saveValidator($extend,$errmsg);
	    if (!$valid) {
            $post["errmsg"]=$errmsg;
            $this->redirect("edit",true,$post);
            die();
        }
        $data=array_merge($post,$extend);
        $success=$this->entity->save($data);
        if (!$success) {
            $post["errmsg"]="保存失败，请重新再试！";
            $this->redirect("edit",true,$post);
            die();
        }
        $id=Filter::int(Req::post("id"));
        if (!$id) $id=$success;

    	$udata=array();
    	if (Req::post("user_id")) {
    		$udata["id"]=Req::post("user_id");
    	} else {
            $udata["gov_id"]=$id;
            $udata["is_admin"]=1;
            $validcode=CHash::random(8);
            $password=Common::generatePWord('123456',$validcode);
    		$udata["password"]=$password;
            $udata["validcode"]=$validcode;
        }
    	$udata["name"]=Filter::sql(Req::post("user_name"));
    	$udata["head_pic"]=Req::post("head_pic");
    	$entity=new GovUserEntity();
    	$ok=$entity->save($udata);
    	echo "<script>parent.close_dialog(true);</script>";
        die();
    	/*
        } else {
    		$post["errmsg"]="保存失败，请重新再试！";
    	}*/
    }

    public function password(){
        $this->layout="blank";
        $id=Filter::int(Req::args("id"));
        $entity=new GovUserEntity();
        $user=$entity->findWhere("gov_id=$id and is_admin=1");
        if ($user) {
            $this->assign("password_id",$user["id"]);
            $this->redirect();
        } else {
            print_r("<div style='text-align: center;'><br><h4>还没有开通管理员账户,无法修改密码！</h4></div>");
            die();
        }
    }

    public function password_act() {
        $id=Filter::int(Req::post("password_id"));
        $newpasswd=Filter::sql(Req::post("newpasswd"));
        $repasswd=Filter::sql(Req::post("repasswd"));
        $success=GovUserService::changeUPwd($id,$newpasswd,$repasswd,$errmsg);
        if ($success) {
            echo "<script>parent.close_pwddialog();</script>";
        } else {
            "<script>swal('$errmsg');parent.close_pwddialog();</script>";
        }
    }
 }