<?php
/**
*附件类
**/
class AttachmentService
{
	/**
	*获得附件列表
	*@param $sourceid: 源记录ID
	*@param $type:类别
	**/
    public static function getAttachment($sourceid,$type) {
        $where=array("source_id"=>$sourceid,"type"=>$type);
        return (new AttachmentEntity())->queryAll($where);
    }
    
}
