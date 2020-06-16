<?php
//用户管理
class AdmmagrController extends AdmBase
{
    public function init() {
        parent::init();
        $this->entity=new ManagerEntity();
    }

    public function test() {
        print_r($_SERVER);
        die();
    }

    public function password_act() {
        $id=Filter::int(Req::post("password_id"));
        $newpasswd=Filter::sql(Req::post("newpasswd"));
        $repasswd=Filter::sql(Req::post("repasswd"));
        $success=ManagerService::changeUPwd($id,$newpasswd,$repasswd,$errmsg);
        if ($success) {
            echo "<script>parent.close_pwddialog();</script>";
        } else {
            Req::post("errmsg",$errmsg);
            $this->redirect("password",true,Req::post());
        }
    }
   
}
