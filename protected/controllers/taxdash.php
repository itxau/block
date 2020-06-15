<?php
/**
* 数据协同协助
**/
class TaxdashController extends TaxHandle
{
    public $attachType=7; //附件文档值

    public function init() {
        parent::init();
        $this->entity=new DatashareEntity();
    }

    //列表信息
    public function index() {
        $this->reassign();
        $ids=ManagerGovernmentService::getManagerGovernment(
            $this->manager["id"],
            $this->manager["is_admin"],
            $this->manager["role_id"]
        );
        if ($ids) Req::args("in__from_id",$ids);
        Req::args("ne__status",1);
        Req::args("assist_id",Constants::TAX_DASH_ID);
        $data=$this->entity->query(Req::args());
        $this->assign("data",$data);
        $this->redirect();
    }

    public function attach_upl() {
        $sourceid=Filter::int(Req::args("sourceid"));
        $addressid=Constants::TAX_ADDR_ID;
        $title=Filter::sql(Req::args("title"));

        $document_id=DocumentService::upload($addressid,$title,$errmsg);
        if (!$document_id) {
            Req::post("errmsg",$errmsg);
            $this->redirect("handle",true,Req::post());
            die();
        }

        $data=array("source_id"=>$sourceid,"type"=>$this->attachType,"document_id"=>$document_id);

        $entity=new AttachmentEntity();
        $entity->save($data);
        $this->redirect("/taxdash/handle/id/$sourceid/upld/1");
    }

}
