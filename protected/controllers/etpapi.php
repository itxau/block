<?php
/*
企业端 API 
*/
class EtpapiController extends Controller
{
	public $layout='';
	public $needRightActions = array('*'=>false);
	public function init()	{
		header("Content-type: text/html; charset=".$this->encoding);
		$act=strtolower(Req::args("act"));
		if ($act!="accesstoken") {
			$token=Filter::sql(Req::args("token"));
	        if (!$token) {
	            $info = array('status'=>0,'msg'=>'输入错误，无法执行请求！',"data"=>null);
	            echo JSON::encode($info);
	            die();
	        }
	        $info=Token::get($token);
	        if ($info!==true) {
	            echo JSON::encode($info);
	            die();
		    }
	    }
	}
  

	//token 请求
	public function accesstoken() {
		$appsecret=Filter::sql(Req::post("appsecret"));
		$appid=Filter::sql(Req::post("appid"));
		if (!$appsecret || !$appid) 
			$info=array('status'=>0,'msg'=>'输入错误,请求无法执行','data'=>null);
		else
			$info=Token::create($appid,$appsecret);
		echo JSON::encode($info);
		die();
	}

    // 用户协议
    public function agreement() {
        $info=["status"=>1,"msg"=>"","data"=>Agreement::article()];
        echo JSON::encode($info);die();
    }

    public function register() {
        $rules = array(
            'account:required:企业号编码不能为空',
            'name:required:企业名称不能为空',
            'full_name:required:企业全称不能为空',
            'mobile:mobi:手机号码不正确!',
            'code:\d{6}:验证码不正确',
            'password:.{6,20}:密码长度必须在6-20位!'
        );
        $info = Validator::check($rules,Req::post());
        if(is_array($info)) {
            $info=["status"=>0,"msg"=>$info["msg"],"data"=>null];
            echo JSON::encode($info);die();
        }

        /*
        $verify=MobileCodeService::verify(Req::post("code"),Req::post("code"),$errmsg);
        if (!$verify) {
            $info=["status"=>0,"msg"=>$errmsg,"data"=>null];
            echo JSON::encode($info);die();
        }
        */
      
        Req::post("id",0);
        $post=Req::post();

        $upfile_path = Core::getPath("headpic");
        $upfile_url  = Core::getPath("headpic_url");
        $filename=Date('Y/m/d/').Crypt::md5(Date("YmdHis").CHash::random(4)).'.jpg';
        file_put_contents($upfile_path.$filename, base64_decode($post["imgfile"]));

        $post["head_pic"]=$upfile_url . $filename;

        $entity=new EnterpriseEntity();
        $extend=array();
        $valid=$entity->saveValidator($extend,$errmsg);
        if ($valid) {
            $data=array_merge($post,$extend);
            $id=$entity->save($data);
            if ($id) {
                EnterpriseService::createAddress($id);
                $info=["status"=>1,"msg"=>"恭喜您，注册成功","data"=>null];
            } else {
                $info=["status"=>0,"msg"=>"对不起，注册失败，请重新再试！","data"=>null];
            }
        } else {
            $info=["status"=>0,"msg"=>$errmsg,"data"=>null];
        }
        echo JSON::encode($info);die();
    }

    //生成企业账号
    public function generatecode() {
        $key=Common::generateAccount();
        $info=["status"=>1,"msg"=>"","data"=>$key];
       echo JSON::encode($info);die();
    }
    
	public function login() {
		$account=Filter::sql(Req::post("account"));
		$password=Filter::sql(Req::post("password"));
        $success=EnterpriseService::login($account,$password,$enterprise,$msg);
        if (!$success) {
            echo JSON::encode(["status"=>0,"msg"=>$msg,"data"=>null]);
            die();
        }

        if (!$enterprise["head_pic"]) { 
            echo JSON::encode(["status"=>0,"msg"=>"你还没有上传头像，不允许登录","data"=>null]);
            die();
        }

        $path=trim($enterprise['head_pic'],'/');
        if (!file_exists($path)) {
            echo JSON::encode(["status"=>0,"msg"=>"你还没有上传头像，不允许登录","data"=>null]);
            die();
        }

        $etp=array(
        	"id"=>$enterprise["id"],
            "account"=>$enterprise["account"],
        	"name"=>$enterprise["name"],
        	"full_name"=>$enterprise["full_name"],
        	"mobile"=>$enterprise["mobile"],
        	"facename"=>CHash::md5("etp_".$enterprise["id"]),
            "faceimage"=>base64_encode(file_get_contents($path)),
            "legalperson"=>"",
            "socialcreditno"=>"",
            "reg_time"=>$enterprise["created_time"]
        );

        $eInfo=(new EnterpriseInfoEntity())->findWhere("etp_id=".$enterprise["id"]);
        if ($eInfo) {
            $etp["legalperson"]=$eInfo["legal_representative"]; //法人
            $etp["socialcreditno"]=$eInfo["socialcredit_no"]; //统一社会信用代码
        }
        echo JSON::encode(["status"=>1,"msg"=>"","data"=>$etp]);
        die();
	}
   
	//发送验证码
	public function verifycode() {
		$mobile=Filter::sql(Req::args("mobile"));
        $type=Filter::sql(Req::args("type"));
        $success=Validator::mobi($mobile);
        if (!$success) {
        	$info=["status"=>0,"msg"=>'手机号码不正确',"data"=>null];
        	echo JSON::encode($info);die();
        }
        $success=MobileCodeService::send($mobile,$type,$msg);
        if ($success)
            $info=["status"=>1,"msg"=>"","data"=>null];
        else
            $info=["status"=>0,"msg"=>$msg,"data"=>null];
        echo JSON::encode($info);
	}

	//修改密码
	public function password() {
		$rules = array(
			'mobile:mobi:手机号码不正确!',
			'code:\d{6}:验证码不正确',
			'password:.{6,20}:密码长度必须在6-20位!'
		);
		$info = Validator::check($rules,Req::post());
		if(is_array($info)) {
            $info=["status"=>0,"msg"=>$info["msg"],"data"=>null];
            echo JSON::encode($info);die();
		}
        $mobile=Filter::sql(Req::post("mobile"));
        $code=Filter::sql(Req::post("code"));
        $password=Filter::sql(Req::post("password"));
        $repassword=Filter::sql(Req::post("repassword"));
        
	    $success=MobileCodeService::verify($mobile,$code,$msg);
        if (!$success) {
        	$info=['status'=>0,'msg'=>$msg,'data'=>null];
            echo JSON::encode($info);die();
        }
        
        $enterprise=(new EnterpriseEntity())->findWhere("(mobile='$mobile')");
        if (!$enterprise) {
        	$info=['status'=>0,'msg'=>'手机未注册','data'=>null];
            echo JSON::encode($info);die();
        }
        $success=EnterpriseService::changeUPwd($enterprise["id"],$password,$repassword,$msg);
        if (!$success) {
        	$info=['status'=>0,'msg'=>$msg,'data'=>null];
        } else {
        	$info=['status'=>1,'msg'=>'密码修改成功！','data'=>null];
        }
        echo JSON::encode($info);die();
        
	}

    //查询人脸验证请求
    public function authrequest(){
        
        $id=Filter::int(Req::args("userid"));
        $queue=FacequeueService::queue(1,$id);
        if ($queue) {
            $info=["status"=>1,"msg"=>"","data"=>$queue];
        } else {
            $info=["status"=>0,"msg"=>"没有查询到人脸验证请求","data"=>null];
        }
        echo JSON::encode($info);die();
        
    }

    //查询人脸验证请求
    public function authresponse(){
        $id=Filter::int(Req::args("reqid"));
        $userid=Filter::int(Req::args("userid"));
        $status=Filter::int(Req::args("status"));
        $msg="";
        $success=FacequeueService::response($id,$userid,$status,$msg);
        if ($success) {
            $info=["status"=>1,"msg"=>"","data"=>null];
        } else {
            $info=["status"=>0,"msg"=>$msg,"data"=>null];
        }
        echo JSON::encode($info);die();
    }

    //私钥查看
    public function privatekey() {
        $rules = array(
            'mobile:mobi:手机号码不正确!',
            'code:\d{6}:验证码不正确！',
            'userid:int:企业信息错误！'
        );
        $info = Validator::check($rules,Req::post());
        if(is_array($info)) {
            $info=["status"=>0,"msg"=>$info["msg"],"data"=>null];
            echo JSON::encode($info);die();
        }

        $mobile=Filter::sql(Req::post("mobile"));
        $code=Filter::sql(Req::post("code"));
        $id=Filter::int(Req::post("userid"));
        $success=MobileCodeService::verify($mobile,$code,$msg);
        if (!$success) {
            $info=['status'=>0,'msg'=>$msg,'data'=>null];
            echo JSON::encode($info);die();
        }
        
        $enterprise=(new EnterpriseEntity())->findWhere("(id=$id) and (mobile='$mobile')");
        if (!$enterprise) {
            $info=['status'=>0,'msg'=>'企业信息错误！','data'=>null];
            echo JSON::encode($info);die();
        }

        $entity=new PrivatekeyEntity();
        $addr=$entity->findWhere("address_id=".$enterprise['address_id']);
        if ($addr) 
            $info=["status"=>1,"msg"=>"","data"=>$addr['privatekey']];
        else
            $info=["status"=>"fail","msg"=>"没有找到密钥信息！","data"=>null];
        echo JSON::encode($info);die();
    }

    //公告信息
    public function announcement() {
        $page=Filter::int(Req::post("page"));
        if (!$page) $page=1;
        $data=FacequeueService::announcement(3,$page);
        echo JSON::encode($data);
        die();
    }

    public function payrecord() {
        $page=Filter::int(Req::post("page"));
        if (!$page) $page=1;
        $etp_id=Filter::int(Req::post("userid"));
        if (!$etp_id) {
            $data=["status"=>"fail","msg"=>"参数输入错误！","data"=>null];
        } else {
            $data=FacequeueService::payrecord($etp_id,$page);
        }
        echo JSON::encode($data);
        die();
    }

    //税务申报
    public function declaretax() {
        $page=Filter::int(Req::post("page"));
        if (!$page) $page=1;
        $etp_id=Filter::int(Req::post("userid"));
        if (!$etp_id) {
             $data=["status"=>"fail","msg"=>"参数输入错误！","data"=>null];
        } else {
            $data=FacequeueService::declaretax($etp_id,$page);
        }
        echo JSON::encode($data);
        die();
    }

    //税务申报
    public function invoicetax() {
        $page=Filter::int(Req::post("page"));
        if (!$page) $page=1;
        $etp_id=Filter::int(Req::post("userid"));
        if (!$etp_id) {
             $data=["status"=>"fail","msg"=>"参数输入错误！","data"=>null];
        } else {
            $data=FacequeueService::invoicetax($etp_id,$page);
        }
        echo JSON::encode($data);
        die();
    }

    //退税业务
    public function refundtax() {
        $page=Filter::int(Req::post("page"));
        if (!$page) $page=1;
        $etp_id=Filter::int(Req::post("userid"));
        if (!$etp_id) {
             $data=["status"=>"fail","msg"=>"参数输入错误！","data"=>null];
        } else {
            $data=FacequeueService::refundtax($etp_id,$page);
        }
        echo JSON::encode($data);
        die();
    }

    //产业认定 15%优惠
    public function discountax() {
        $page=Filter::int(Req::post("page"));
        if (!$page) $page=1;
        $etp_id=Filter::int(Req::post("userid"));
        if (!$etp_id) {
             $data=["status"=>"fail","msg"=>"参数输入错误！","data"=>null];
        } else {
            $data=FacequeueService::discountax($etp_id,$page);
        }
        echo JSON::encode($data);
        die();
    }

    //企业数量
    public function documentdata() {
        $page=Filter::int(Req::post("page"));
        if (!$page) $page=1;
        $etp_id=Filter::int(Req::post("userid"));
        if (!$etp_id) {
             $data=["status"=>"fail","msg"=>"参数输入错误！","data"=>null];
        } else {
            $data=FacequeueService::documentdata($etp_id,$page);
        }
        echo JSON::encode($data);
        die();
    }

    //企业数量
    public function videomeeting() {
        $page=Filter::int(Req::post("page"));
        if (!$page) $page=1;
        $data=FacequeueService::videomeeting(1,$page);
        echo JSON::encode($data);
        die();
    }
	
}
