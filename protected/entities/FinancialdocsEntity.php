<?php
class FinancialdocsEntity extends BaseEntity
{
    protected $table="etp_financialdocs";
    protected $table_zh="企业财务上链文档";
    protected $alias="fds";

    protected $rights = array("insert","changestatus");

    public $export_fields = array();

    protected function buildModel(&$model,&$fields,&$join) {
        $model=new Model("etp_financialdocs as fds");
        $fields="fds.*,fdt.doctype_name,fdt.reptype_name,etp.name as etpname,etp.full_name,doc.address_id,doc.title,doc.txid,doc.filename,doc.filesize,doc.mimetype,doc.ext";
        $join=" left join etp_financialdocstype as fdt on fds.doctype_id=fdt.doctype_id and fds.reptype_id=fdt.reptype_id";
        $join.=" inner join etp_enterprise as etp on fds.etp_id=etp.id";
        $join.=" inner join blc_document as doc on fds.document_id=doc.id";
        return true;
    }

}
