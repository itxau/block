<?php
class FinancialdocsService
{
    public static function documentTypeMap() {
        $model=new Model("etp_financialdocstype");
        $docs=$model->fields("distinct doctype_id,doctype_name")->order("doctype_id")->findAll();
        $map=array();
        foreach ($docs as $key => $doc) {
            $map[$doc['doctype_id']]=$doc['doctype_name'];
        }
        return $map;
    }

    public static function reportTypeMap($doc_type) {
        $model=new Model("etp_financialdocstype");
        $docs=$model->fields("reptype_id,reptype_name")->where("doctype_id=$doc_type")->order("reptype_id")->findAll();
       
        $map=array();
        foreach ($docs as $key => $doc) {
            $map[$doc['reptype_id']]=$doc['reptype_name'];
        }
        return $map;
    }
}
