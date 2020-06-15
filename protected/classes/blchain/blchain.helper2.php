<?php
class BlcHelper
{
    public static function createAddress() {

        //$publicKey="",$privteKey="";
        $success=BlcRSA::createRSAKey($publicKey,$privteKey);
        if (!$success) return false;

        $address="";
        $fsa=new BlcSteamAPI();
        $success=$fsa->createAddress($address);
        if (!$success) return false;

        $streamKey=Common::datetime2String("S");
        $success=$fsa->createStream($address,$streamKey);
        if (!$success) return false;

        $entity=new AddressEntity();
        $data=array(
            "address"=>$address,
            "streamkey"=>$streamKey,
            "privatekey"=>$privteKey,
            "publickey"=>$publicKey
        );
        return $entity->save($data);
    }
 
    //文档数据上链
    public static function publishFile($address_id,$filename,$filepath,$mimetype) {

        $entity=new AddressEntity();
        $address=$entity->find($address_id);
        if (!$address) return false;

        $optionKey=Common::generateUniqueNo("K");

        $content=file_get_contents($filepath);
        $encrypted="";
        $success=BlcRSA::encrypt($address["publickey"],$content,$encrypted);
        if (!$success) return false;

        $data="\x00".$filename."\x00".$mimetype."\x00".$encrypted;

        $fsa=new BlcSteamAPI();
        $txid=$fsa->publish($address["address"],$address["streamkey"],$optionKey,bin2hex($data));
        if ($txid===false) 
            return false;
        else
            return $txid;
    }

    //文档下载
    public static function downFile($address_id,$txid) {

        $entity=new AddressEntity();
        $address=$entity->find($address_id);
        if (!$address) return false;

        $fsa=new BlcSteamAPI();
        $data=null;
        try {
            $data=$fsa->chainMethod("gettxoutdata",$txid,0);
            if (!$data) return false;
        } catch(Exception $e) {
            return false;
        }

        $decrypted="";
        $success=BlcRSA::decrypt($address["privatekey"],pack('H*', $data["result"]),$decrypted);
        if (!$success) return false;
        
        $parts=explode("\x00", $decrypted, 4);
        if ( (count($parts)!=4) || ($parts[0]!='') ) return false;
        
        $file= array(
            'filename' => $parts[1],
            'mimetype' => $parts[2],
            'content'  => $parts[3]
        );
        if (is_array($file)) {
            if (strlen($file['mimetype'])) {
                header('Content-Type: '.$file['mimetype']);
            }
            if (strlen($file['filename'])) {
                $filename=preg_replace('/[^A-Za-z0-9 \\._-]+/', '', $file['filename']);
                header("Content-Disposition: attachment; filename=".$filename);
            }
            echo $file['content'];
            return true;
        } else {
            return false;
        }
    }
   
}