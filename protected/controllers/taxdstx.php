<?php
/**
* 企业15%所得税优惠申请  企业->税务局->管里局
**/
class TaxdstxController extends TaxHandle
{
    public $attachType=5;
    public function init() {
        parent::init();
        $this->entity=new DiscountaxEntity();
    }

    public function attach_upl() {

        $sourceid=Filter::int(Req::args("sourceid"));
        $type=52;
        $addressid=Constants::TAX_ADDR_ID;
        $title=Filter::sql(Req::args("title"));

        $document_id=DocumentService::upload($addressid,$title,$errmsg);
        if (!$document_id) {
            Req::post("errmsg",$errmsg);
            $this->redirect("handle",true,Req::post());
            die();
        }

        $data=array("source_id"=>$sourceid,"type"=>$type,"document_id"=>$document_id);

        $entity=new AttachmentEntity();
        $entity->save($data);
        $this->redirect("/taxdstx/handle/id/$sourceid/upld/1");
    }

    

}
