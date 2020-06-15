<?php
class EnterpriseEntity extends BaseEntity
{
    protected $table="etp_enterprise";
    protected $table_zh="企业";
    protected $alias="etp";

    //状态列表
    public $statusMap = array (
        "1"=>"已接入",
        "2"=>"审核中",
        "3"=>"已禁止"
    );

    protected $rights = array("insert","edit","changestatus");

    protected function buildModel(&$model,&$fields,&$join) {
        $model=new Model("$this->table as $this->alias");
        $fields="*";
        $join=" left join etp_enterprise_info as eti on $this->alias.id=eti.etp_id";
        return true;
    }
    
    //保存前验证
    public function saveValidator(&$extend,&$errmsg){
        $id=Filter::int(Req::post("id"));
        $mobile=Filter::sql(Req::post("mobile"));
        if ($mobile) {
        $where="(id<>$id) and (mobile='$mobile')";
            $m=$this->findWhere($where);
            if ($m) {
                $errmsg="手机号码已经被使用,请使用其他号码！";
                return false;
            }
        }

        if (!$id) {
            $where="account='".Filter::sql(Req::post("account"))."'";
            $m=$this->findWhere($where);
            if ($m) {
                $errmsg="账户已经被注册,请使用其他账号！";
                return false;
            }
            $name=Filter::sql(Req::post("name"));
            $extend["name"]=$name;
            //$extend["full_name"]=$name;
            $extend["status"]=1;
            $validcode=CHash::random(8);
            $password=Common::generatePWord(Req::post("password"),$validcode);
            $extend["validcode"]=$validcode;
            $extend["password"]=$password;
        }
        return true;
    }

  
    //更新最后登录 IP + 时间
    public function updateLogin($id,$ip) {
        $model = new Model($this->table);
        $data=array(
            'last_ip' => $ip, 
            'last_login' => date("Y-m-d H:i:s")
        );
        $flag=$model->where("id=$id")->data($data)->update();
        return $flag;
    }

    public function password($id,$password) {
        $data=array("password"=>$password);
        return $this->update($id,$data);
    }

    

}
