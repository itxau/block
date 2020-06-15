<?php
class DocumentService
{
    public static function upload(int $address_id,$title,&$errmsg) {
        $upload=$_FILES['upfile'];
        $filepath=$upload['tmp_name'];
        $filename=$upload['name'];
        $mimetype=$upload['type'];
        $ext=pathinfo($filename, PATHINFO_EXTENSION);
        $filesize=filesize($filepath);
        if ($filesize==0) {
            $errmsg="文件内容为空！";
            return false;
        }
        if($filesize>1024*1024) {
            $errmsg="文档太大，请上传小于 1M 的文档！";
            return false;
        }
        $txid=BlcHelper::publishFile($address_id,$filename,$filepath,$mimetype);
        if ($txid===false) {
            $errmsg="上链失败！";
            return false;
        }
        if (!$title) $title=rtrim($filename,".".$ext);
        $data=compact("address_id","filename","filesize","mimetype","txid","ext","title");

        $entity=new DocumentEntity();
        return $entity->save($data);
    }
   
}
