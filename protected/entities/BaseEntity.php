<?php
class BaseEntity
{
    protected $table="";
    protected $table_zh="";
    protected $alias="";

    public $lableMap = array(
        "0"=>"default",
        "1"=>"success",
        "2"=>"danger",
        "3"=>"info",
        "4"=>"warning",
        "5"=>"primary"
    );

    public $statusMap = array (
        "1"=>"正常",
        "2"=>"禁止"
    );

    public $exportFields = array();

    protected $rights = array("insert","edit","delete","changestatus","deleteWhere");

    protected $orderSort=" created_time desc ";

    protected $deleteStatus = "(status=1)";

    protected $hasStatus=true;

    protected $defaultStatus=1;

    public function __construct(){
    }

    /*--------------------------------*/

    protected function buildModel(&$model,&$fields,&$join) {
        $model=new Model($this->table);
        $fields="*";
        $join="";
        return true;
    }

    protected function parseRow($row) {
        if ($this->hasStatus && array_key_exists("status",$row) && $this->statusMap) {
            $row["statusname"]=Common::statusMap($this->statusMap,$row["status"]);
            $row["label"]=Common::statusMap($this->lableMap,$row["status"],"warning");
        }
        return $row;
    }

    public function deleteBeforeGov($gov_id,$ids) {
        $success=$this->parseIds($ids,$where);
        if (!$success) return false;
        $where="(gov_id<>$gov_id) and ".$where;
        $obj=$this->findWhere($where,"id");
        return empty($obj);
    }

    public function deleteValidator($ids){
        if (!$this->hasStatus) return true;
        $success=$this->parseIds($ids,$where);
        if (!$success) return false;
        if ($this->deleteStatus) $where=$this->deleteStatus." and ".$where;
        //print_r($where);die();
        $obj=$this->findWhere($where,"id");
        return (empty($obj)) ;
    }

    public function saveValidator(&$extend,&$errmsg){
        return true;
    }

    /*-----------------------*/

    //
    public function deleteWhere($where) {
        if (!$this->checkRight("deleteWhere")) return false;
        $model=new Model($this->table);
        return $model->where($where)->delete();
    }

    public function delete($ids) {
        if (!$this->checkRight("delete")) return false;
        $success=$this->parseIds($ids,$where);
        if (!$success) return false;
        $model=new Model($this->table);
        return $model->where($where)->delete();
    }

    public function update($id,$data) {
        $where="(id=$id)";
        return $this->updateWhere($where,$data);
    }

    public function updateWhere($where,$data) {
        if (!$this->checkRight("edit")) return false;
        $model=new Model($this->table);
        return $model->data($data)->where($where)->update();
    }

    public function save($data) {
        if (!array_key_exists("updated_time", $data)) {
            $data["updated_time"]=date("Y-m-d H:i:s");
        }
        if (array_key_exists("id", $data) && $data["id"]) {
            $id=$data["id"];
            unset($data["id"]);
            $result=$this->update($id,$data);
        } else {
            if (!$this->checkRight("insert")) return false;
            if (!array_key_exists("status", $data)) {
                $data["status"]=$this->defaultStatus;
            }
            if (!array_key_exists("created_time", $data)) {
                $data["created_time"]=date("Y-m-d H:i:s");
            }
            $model=new Model($this->table);
            $result=$model->data($data)->insert();
        }
        return $result;
    }

    public function find($id, $fields="*"){
        $where="(id=$id)";
        return $this->search($where,$fields);
    }

    public function findWhere($where,$fields="*"){
        return $this->search($where,$fields);
    }

    public function queryFind($conditions=null) {

        $conditions=$this->unsetConditions($conditions);
        $where=$this->buildWhere($conditions);
       
        $this->buildModel($model,$fields,$join);

        $obj=$model->fields($fields)->where($where)->join($join)->find();
        
        if($obj) {
            $obj=$this->parseRow($obj);
        }        
        return $obj; 
    }

    public function queryAll($conditions=null,$order="",$limit=0) {

        $conditions=$this->unsetConditions($conditions);
        $where=$this->buildWhere($conditions);
        //print_r($where);die();
       
        $this->buildModel($model,$fields,$join);

        if (!$order) $order=$this->orderSort;

        if ($limit) 
            $data=$model->fields($fields)->where($where)->join($join)->order($order)->limit($limit)->findAll();
        else
            $data=$model->fields($fields)->where($where)->join($join)->order($order)->findAll();
        foreach ($data as $key => $item) {
            $data[$key]=$this->parseRow($item);
        }        
        return $data; 
    }

    public function query($conditions=null, $order="") {

        $page=1;
        if (array_key_exists("page", $conditions)) {
            if ($conditions['page']) $page=intval($conditions['page']);
            if ($page<1) $page=1;
        }

        $isexport=false;
        if ($this->exportFields) {
            if (array_key_exists("submittype", $conditions)) {
                $isexport=Filter::sql($conditions['submittype'])=="export";
            }
        }

        $conditions=$this->unsetConditions($conditions);
        $where=$this->buildWhere($conditions);

        $this->buildModel($model,$fields,$join);

        if (!$order) $order=$this->orderSort;

        if ($isexport)
            $data=$model->fields($fields)->where($where)->join($join)->order($order)->findAll();
        else {
            $pagedata=$model->fields($fields)->where($where)->join($join)->order($order)->findPage($page);
            $data=$pagedata['data'];
        }

        foreach ($data as $key => $item) {
            $data[$key]=$this->parseRow($item);
        }
                       
        if ($isexport)
            return $data; 
        else {
            $pagedata['data']=$data;
            return $pagedata;
        }
    }

    public function changeStatus($ids,$status) {
        if (!$this->checkRight("changestatus")) return false;
        if (!array_key_exists($status, $this->statusMap)) return false;
        $success=$this->parseIds($ids,$where);
        if (!$success) return false;
        $data=array("status"=>$status);
        $model=new Model($this->table);
        return $model->data($data)->where($where)->update();
    }

    public function count($where=""){
        if(!$where) $where="1=1";
        $model=new Model($this->table);
        return $model->where($where)->count();
    }

    /*-------------------------*/

    private function checkRight($op) {
        return in_array($op, $this->rights);
    }

    public function parseIds($ids,&$idsWhr,$idname="id") {
        if(is_array($ids)){
            foreach ($ids as $key => $id) {
                if (!is_numeric($id)) return false;
            }
            $idsWhr="($idname in (".implode(",",$ids)."))";
        }
        elseif (is_numeric($ids)){
            $idsWhr="($idname=$ids)";
        } else {
            return false;
        }
        return true;
    }

    private function search($where,$fields){
        $model=new Model($this->table);
        $obj=$model->fields($fields)->where($where)->find();
        if (!$obj) 
            return $obj;
        else
            return $this->parseRow($obj);
    }

    private function parseOperator($op) {
        $sourceOps=array('tt','ne','eq','lt','gt','le','ge','ct','nct','be','in','ni');
        $targetOps=array("tt"," <> '"," = '"," < '"," > '"," <= '"," >= '"," like '%"," not like '%","be"," in ("," not in (");
        $idx=array_search($op, $sourceOps);
        if ($idx)
            return $targetOps[$idx];
        else
            return "";
    }

    private function parseTerms($terms,$val) {
        $arr=explode("__",$terms);
        if (count($arr)==2) {
            if ($arr=="no") {
                return ""; // 不是查询条件
            } else {
                $op=$this->parseOperator($arr[0]);
                $field=$arr[1];
            }
        } else {
            $op=" = '";
            $field=$arr[0];
        }
        if (!$op) return "";
        if (strpos($field,"_d_")) {
            $field=str_replace("_d_",".",$field);
        } else {
            if ($this->alias) $field="$this->alias.$field";
        }
        if ($op=="be") {
            $vals=explode(" -- ",$val);
            if (count($vals)==2)
                $result=$field. " between '".$vals[0]."' and '".$vals[1]." 23:59:59'";
            else
                $result="";
        } else {
            $result=$field.$op.$val; 
            if (strpos($op,"%")) $result.="%";
            if (strpos($op,"(")) $result.=")";
            if (strpos($op,"'")) $result.="'";
        }
        return $result;
    }

    private function parseCondition($condition,$val) {
        $result="";
        $fields=explode("_or_",$condition);
        foreach ($fields as $key => $field) {
            if ($result)
                $result.=" or ". $this->parseTerms($field,$val);
            else
                $result=$this->parseTerms($field,$val);
        }
        return $result?" and ($result) ":"";
    }

    private function buildWhere($conditions) {

        $where="(1=1)";

        if (!$conditions || !is_array($conditions)) return $where;
             
        foreach ($conditions as $key => $value) {
            $val=Filter::sql($value);
            if (!$val) continue;
            $where.=$this->parseCondition(strtolower($key),$val);
        }

        return $where;
    }

    private function unsetConditions($conditions) {
        unset($conditions['page']);
        unset($conditions['submittype']);
        unset($conditions['con']);
        unset($conditions['act']);
        return $conditions;
    }
}
