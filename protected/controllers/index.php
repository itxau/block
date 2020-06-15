<?php

class IndexController extends Controller
{
	public $layout='';

    public function test() {
        //local
        /*
       print_r(Crypt::encode("127.0.0.1","host")."<br>");
       print_r(Crypt::encode("root","user")."<br>");
       print_r(Crypt::encode("root","password")."<br>");
       print_r(Crypt::encode("qhtax","name")."<br>");
       */
       //kylin
       print_r(Crypt::encode("100.100.100.182","host")."<br>");
       print_r(Crypt::encode("tax@2020","user")."<br>");
       print_r(Crypt::encode("Prd@TaxDB2020$#","password")."<br>");
       print_r(Crypt::encode("qhtaxdb","name")."<br>");
       die();
    }

    public function index() {
    	//$this->redirect("/enterprise/index");
        $this->redirect();
    }

    public function government(){
    	$this->redirect();
    }

    //发送验证吗
    public function sendsms() {
        $mobile=Filter::sql(Req::args("mobile"));
        $type=Filter::sql(Req::args("type"));
        $success=MobileCodeService::send($mobile,$type,$errmsg);
        if ($success)
            $info=["status"=>"success","msg"=>"验证码已发送！"];
        else
            $info=["status"=>"fail","msg"=>$errmsg];
        echo JSON::encode($info);
    }
    
    public function upload_headpic() {
        $upfile_path = Core::getPath("headpic");
        $upfile_url  = Core::getPath("headpic_url");
        $upfile      = new UploadFile('imgFile', $upfile_path, '10m');
        $upfile->save();
        $info   = $upfile->getInfo();
        if ($info[0]['status'] == 1) {
            $info['img'] = $upfile_url . $info[0]['path'];
        } else {
            $info['error'] = array('code' => '103', 'message' => $info[0]['msg']);
        }
        echo JSON::encode($info);
    }

    public function downfile() {
        $txid=Req::args("doctxid");
        $addid=Req::args("addressid");
        $key=Req::args("priavtekey");
        if (!$txid || !$key || !$addid) {
            //echo "<script>swal('输入错误')</script>";die();
            print_r("密钥输入错误，无法下载！");die();
        }
        
        $success=BlcHelper::downFile($addid,$key,$txid);
        if (!$success) {
             print_r("密钥输入错误，无法下载！");die();
        }
    }
  
   
    
   
}
