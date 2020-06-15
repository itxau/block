<?php
/**
 * 税务受理业务基类
 *
 * @author sun xian gen
 */
class TaxHandle extends TaxBase
{
    public $attachType=0; //附件文档值
    //列表信息
    public function index() {
        $ids=ManagerEnterpriseService::getManagerEnterprise(
            $this->manager["id"],
            $this->manager["is_admin"],
            $this->manager["role_id"]
        );
        if ($ids) Req::args("in__etp_id",$ids);
        Req::args("ne__status",1);
        parent::index();
    }

    //申请处理
    public function handle() {
        $this->layout="blank";
        $id=Filter::int(Req::args("id"));
        $data=$this->entity->find($id);
        $data["upld"]=Filter::int(Req::args("upld"));
        $this->redirect("handle",false,$data);
    }

    //删除记录
    public function delete() {
        $info=["status"=>"fail","msg"=>"对不起，你无权记此录删！"];
        echo JSON::encode($info);
    }

    public function save()  {
        $this->checkFormToken();
        $data=array(
            "id"=>Req::post("id"),
            "status"=>Req::post("status"),
            "approved_by"=>$this->manager["id"],
            "approved_note"=>Req::post("approved_note"),
            "approved_time"=>date("Y-m-d H:i:s")
        );
        $this->entity->save($data);
        echo "<script>parent.close_dialog(true);</script>";
    }

    //修改记录状态
    public function status() {
        $info=["status"=>"fail","msg"=>"对不起，状态修改失败！"];
        echo JSON::encode($info);
    }

}
