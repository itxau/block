<?php
class GovRoleEntity extends BaseEntity
{
    protected $table="gov_role";
    protected $table_zh="角色";

    public function deleteValidator($ids){

        if (!parent::deleteValidator($ids)) return false;

        $success=$this->parseIds($ids,$where,"role_id");
        if (!$success) return false;

        $entity=new GovUserEntity();
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
        $gov_id=Filter::int(Req::post("gov_id"));
        $where="(id<>$id) and (gov_id=$gov_id) and  (name='$name')";
        $obj=$this->findWhere($where,"id");
        if ($obj) {
            $errmsg="角色名称 '$name' 已经被使用！";
            return false;
        }
        $right = Req::args("right");
        if (is_array($right)) {
            $right = implode(',', $right);
        }
        $model  = new Model("gov_right");
        $res    = $model->where("id in($right)")->findAll();
        $rights = '';
        foreach ($res as $re) {
            $rights .= ',' . $re['rights'];
        }
        $rights = explode(',', trim($rights, ','));
        $rights = array_unique($rights);
        $rights = implode(',', $rights);
        $extend["rights"]=$rights;
      
        return true;
    }

   
}
