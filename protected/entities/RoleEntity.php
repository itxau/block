<?php
class RoleEntity extends BaseEntity
{
    protected $table="adm_role";
    protected $table_zh="角色";

    protected function parseRow($row) {

        $row=parent::parseRow($row);

        if (array_key_exists("is_grantofetp", $row) && $row['is_grantofetp']) 
            $row['grantofetp']='是';
        else
            $row['grantofetp']=' 否';
        if (array_key_exists("is_grantofgov", $row) && $row['is_grantofgov']) 
            $row['grantofgov']='是';
        else
            $row['grantofgov']=' 否';

        return $row;
        
    } 

    public function deleteValidator($ids){

        if (!parent::deleteValidator($ids)) return false;

        $success=$this->parseIds($ids,$where,"role_id");
        if (!$success) return false;

        $entity=new ManagerEntity();
        $obj=$entity->findWhere($where,"id");
        return empty($obj);
    }
   
    public function saveValidator(&$extend,&$errmsg){
        $id=0;
        if (array_key_exists("id", Req::post())) {
            $id=Filter::int(Req::post("id"));
        }
        $name=Filter::sql(Req::post("name"));
        if (!$name) {
            $errmsg="角色名称输入无效！";
            return false;
        }
        $extend["name"]=$name;
        $where="(id<>$id) and (name='$name')";
        $obj=$this->findWhere($where,"id");
        if ($obj) {
            $errmsg="角色名称 '$name' 已经被使用！";
            return false;
        }
        if (!array_key_exists("is_grantofetp", Req::post())) {
            $extend["is_grantofetp"]=0;
        }
        if (!array_key_exists("is_grantofgov", Req::post())) {
            $extend["is_grantofgov"]=0;
        }

        $right = Req::args("right");
        if (is_array($right)) {
            $right = implode(',', $right);
        }
        if ($right) {
            $model  = new Model("adm_right");
            $res    = $model->where("id in($right)")->findAll();
            $rights = '';
            foreach ($res as $re) {
                $rights .= ',' . $re['rights'];
            }
            $rights = explode(',', trim($rights, ','));
            $rights = array_unique($rights);
            $rights = implode(',', $rights);
        } else {
            $rights="";
        }
        $extend["rights"]=$rights;
      
        return true;
    }

   
}
