<?php
class BlcHelper
{
    public static function createAddress() {

        //$publicKey="",$privteKey="";
        $success=BlcRSA::createRSAKey($publicKey,$privteKey);
        if (!$success) return false;

        $uuid=CHash::uuid();
        $success=BlcRSA::encrypt($publicKey,$uuid,$secretkey);
        if (!$success) return false;

        $address="";
        $fsa=new BlcSteamAPI();
        $success=$fsa->createAddress($address);
        if (!$success) return false;

        $streamKey=Common::datetime2String("S");
        $success=$fsa->createStream($address,$streamKey);
        if (!$success) return false;

        return AddressService::save($address,$streamKey,$secretkey,$publicKey,$privteKey);

    }
 
    //文档数据上链
    public static function publishFile($address_id,$filename,$filepath,$mimetype) {

        $address=AddressService::getAddressInfo($address_id);
        if (!$address) return false;

        $privatekey=AddressService::getPrivateKey($address_id);
        if (!$privatekey) return false;

        $success=BlcRSA::decrypt($privatekey,$address["secretkey"],$secretkey);
        if (!$success) return false;

        $aes=new BlcAES($secretkey);
        $optionKey=Common::generateUniqueNo("K");

        $content=file_get_contents($filepath);
        $encrypted=$aes->filecrypt($content);
        if ($encrypted===false) return false;

        $data="\x00".$filename."\x00".$mimetype."\x00".$encrypted;

        $fsa=new BlcSteamAPI();
        $txid=$fsa->publish($address["address"],$address["streamkey"],$optionKey,bin2hex($data));
        if ($txid===false) 
            return false;
        else
            return $txid;
    }

    //文档下载
    public static function downFile($address_id,$privatekey,$txid) {

        $address=AddressService::getAddressInfo($address_id);
        if (!$address) return false;

        $prikey=AddressService::getPrivateKey($address_id);
        //print_r($prikey);die();
        if (!$prikey || $prikey!=$privatekey) return false;


        
        try {
            $success=BlcRSA::decrypt($privatekey,$address["secretkey"],$secretkey);
            if (!$success) return false;
        } catch(Exception $e) {
            return false;
        }

        $aes=new BlcAES($secretkey);

        //print_r("expression");die();

        $fsa=new BlcSteamAPI();
        $data=null;
        try {
            $data=$fsa->chainMethod("gettxoutdata",$txid,0);
            if (!$data) return false;
        } catch(Exception $e) {
            return false;
        }

        $content=pack('H*', $data["result"]);
        $parts=explode("\x00", $content, 4);
        if ( (count($parts)!=4) || ($parts[0]!='') ) return false;

        $filedata=$aes->filedecrypt($parts[3]);
        if ($filedata===false) return false;
        
        $filename = str_replace(",", "，", $parts[1]) ;
        $mimetype = $parts[2];

        header('Content-Type: '.$mimetype);
        header("Content-Disposition: attachment; filename=".$filename);
        echo $filedata;
        return true;
        
    }
   
}