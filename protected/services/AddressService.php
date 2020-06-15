<?php
class AddressService
{
    //保存地址 + 密钥
    public static function save($address,$streamKey,$secretkey,$publicKey,$privteKey) {
        
        $entity=new AddressEntity();
        $data=array(
            "address"=>$address,
            "streamkey"=>$streamKey,
            "secretkey"=>$secretkey,
            "publickey"=>$publicKey
        );
        $address_id=$entity->save($data);
        if (!$address_id) return false;
        
        $entity=new PrivatekeyEntity();
        $success=$entity->save(["address_id"=>$address_id,"privatekey"=>$privteKey]);
        if ($success)
            return $address_id;
        else
            return false;

    }

    public static function getAddressInfo($address_id) {
        $entity=new AddressEntity();
        return $entity->find($address_id);
    }

    public static function getPrivateKey($address_id) {
        $entity=new PrivatekeyEntity();
        $obj=$entity->findWhere("address_id=$address_id");
        if ($obj && $obj["privatekey"]) 
            return $obj["privatekey"];
        else 
            return false;
    }
   
}
