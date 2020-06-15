<?php
class GovUserEntity extends BaseEntity
{
    protected $table="gov_user";
    protected $table_zh="用员";
    protected $alias="usr";

    protected $orderSort=" usr.created_time desc ";

    protected function buildModel(&$model,&$fields,&$join) {
        $model=new Model("$this->table as $this->alias");
        $fields="$this->alias.*,rle.name as rolename,dpt.name as deptname";
        $join=" left join gov_role as rle on $this->alias.role_id=rle.id";
        $join.=" left join gov_dept as dpt on $this->alias.dept_id=dpt.id";
        return true;
    }

    public function deleteValidator($ids){
        if(is_array($ids)){
            foreach ($ids as $key => $id) {
                if (!is_numeric($id)) return false;
            }
            $where="(status=1 or is_admin=1) and (id in (".implode(",",$ids)."))";
        }
        elseif (is_numeric($ids)){
            $where="(status=1 or is_admin=1) and (id=$ids)";
        } else {
            return false;
        }

        $chs=$this->findWhere($where,"id");
        if ($chs) return false;
      
        /*
        $opl=new OperationlogEntity();
        $this->parseIds($ids,$where,"manager_id");
        $obj=$opl->findWhere($where,"id");
        return empty($obj);
        */
        return true;
    }
    protected function parseRow($row) {

    	$row=parent::parseRow($row);

    	if (array_key_exists("is_msgofetp", $row) && $row['is_msgofetp']) 
    		$row['msgofetp']='是';
    	else
    		$row['msgofetp']=' 否';
    	if (array_key_exists("is_msgofadm", $row) && $row['is_msgofadm']) 
    		$row['msgofadm']='是';
    	else
    		$row['msgofadm']=' 否';

        return $row;
        
    } 

    public function saveValidator(&$extend,&$errMsg){
        $id=Filter::int(Req::post("id"));
        $gov_id=Filter::int(Req::post("gov_id"));

        $name=Filter::sql(Req::post("name"));
        if (!$name) {
            $errMsg="用户名称输入错误";
            return false;
        }
        $obj=$this->findWhere("(id<>$id) and (gov_id=$gov_id) and (name='$name')","id");
        if ($obj) {
            $errMsg="用户名称已存在！";
            return false;
        }

        if (!$id) {
            $password=Filter::sql(Req::post("password"));
            $repassword=Filter::sql(Req::post("repassword"));
            if (!$password) {
                $errMsg="用户密码输入错误！";
                return false;
            }
            if ($password!=$repassword) {
                $errMsg="密码输入不一致！";
                return false;
            }
            $validcode=CHash::random(8);
            $password=Common::generatePWord($password,$validcode);
            $extend=array("password"=>$password,"validcode"=>$validcode);
        }
        $extend["name"]=$name;

        if (!array_key_exists("is_msgofetp", Req::post())) {
    		$extend["is_msgofetp"]=0;
    	}
    	if (!array_key_exists("is_msgofadm", Req::post())) {
    		$extend["is_msgofadm"]=0;
    	}
        return true;
    }

}
