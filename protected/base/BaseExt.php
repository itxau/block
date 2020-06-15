<?php


/**
 * 操作基类，负责控制器的基本操作
 *
 * @author sun xian gen
 */
class BaseExt extends Controller
{
	
	public $entity;
   

    //当前 url
    public function currentUrl() {
        $con=Req::get('con')==null?strtolower(Core::app()->defaultController):Req::get('con');
        $act=Req::get('act')==null?Core::app()->getController()->defaultAction:Req::get('act');
        return strtolower('/'.$con.'/'.$act);
    }
  
    //是否为导出操作
    private function checkexport(){
        if (!empty($this->entity->exportFields) &&
            array_key_exists("submittype", Req::args()) && 
            Req::args("submittype")=="export")
            return true;
        else
            return false;
    }
   

    //导出数据
    private function export($data) {
        $xlsname  = date('YmdHis');
        $filename = date('YmdHis');
        $excel    = new Excel(
            $xlsname, 
            $this->entity->export_fields, 
            $data, 
            $filename
        );
        $excel->excel_export();
    }

    //列表信息
  	public function index() {
        $this->reassign();
        $data=$this->entity->query(Req::args());
        if ($this->checkexport()) {
            $this->export($data);
        } else {
            $this->assign("data",$data);
            $this->redirect();
        }
    }

    //修改记录
    public function edit()  {
    	$this->layout="blank";
        $id=Filter::int(Req::args("id"));
        $data=array();
        if ($id) {
            $data=$this->entity->find($id);
        }
        $this->redirect("edit",false,$data);
    }

    //申请处理
    public function handle() {
        $this->layout="blank";
        $id=Filter::int(Req::args("id"));
        $data=$this->entity->find($id);
        $this->redirect("handle",false,$data);
    }

    //记录详情
    public function info() {
        $this->layout="blank";
        $id=Filter::int(Req::args("id"));
        $data=array();
        if ($id) {
            $data=$this->entity->find($id);
        }
        $this->redirect("info",false,$data);
    }

    //删除记录
    public function delete() {
        $ids=Req::args("ids");
        $valid=$this->entity->deleteValidator($ids);
        if (!$valid) {
            $info=["status"=>"fail","msg"=>"有效记录或被引用的记录不允许删除！"];
        } else {
            $success=$this->entity->delete($ids);
            if ($success){
                $info=["status"=>"success","msg"=>"所选记录已经成功删除！"];
            } else {
                $info=["status"=>"fail","msg"=>"对不起记录删除失败！"];
            } 
        }
        echo JSON::encode($info);
    }

    public function password() {
        $password_id=Filter::int(Req::args("password_id"));
        $this->assign("password_id",$password_id);
        $this->layout="blank";
        $this->redirect();
    }

    public function save()  {
        $this->checkFormToken();
        $post=Req::post();
        $extend=array();
        $valid=$this->entity->saveValidator($extend,$errmsg);
	    if ($valid) {
            $data=array_merge($post,$extend);
	        $success=$this->entity->save($data);
	        if ($success) {
                echo "<script>parent.close_dialog(true);</script>";
	        } else {
                $data["errmsg"]="保存失败，请重新再试！";
	            $this->redirect("edit",true,$data);
	        }
    	} else {
            $post["errmsg"]=$errmsg;
    		$this->redirect("edit",true,$post);
    	}
    }

    //修改记录状态
    public function status() {
    	$ids = Req::post("ids");
		$status = Filter::int(Req::post("status"));
		$success=$this->entity->changeStatus($ids,$status);
        if ($success){
            $info=["status"=>"success","msg"=>"状态修改成功！"];
        } else {
            $info=["status"=>"fail","msg"=>"对不状态修改失败！"];
        }
        echo JSON::encode($info);
    }

     //清理缓存
    public function clear()
    {
        $this->redirect();
    }

    public function clear_act()
    {
        $type = Req::args('type');
        $info = array('status' => 'fail', 'msg' => '缓存清理失败!');

        if ($type == 'data') {
            if (File::rmdir(APP_ROOT . 'cache')) {
                $info = array('status' => 'success', 'msg' => '数据缓存已成功清理!');
            }
        } else if ($type == 'theme') {
            if (File::rmdir(APP_ROOT . 'runtime')) {
                $info = array('status' => 'success', 'msg' => '模板缓存已成功清理!');
            }
        } else {
            if (File::rmdir(APP_ROOT . 'runtime') && File::rmdir(APP_ROOT . 'cache')) {
                $info = array('status' => 'success', 'msg' => '数据及模板缓存已成功清理!');
            }
        }
        echo JSON::encode($info);
    }

    //查询重新赋值
	public function reassign() {
		foreach (Req::args() as $key => $value) {
			$this->assign($key,$value);
		}
		$this->assign("submittype","search");
	}

}
