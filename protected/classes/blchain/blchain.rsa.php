<?php
class BlcRSA
{
    //私钥解密
    public static function decrypt($privteKey,$data,&$decrypted) {

        $data=base64_decode($data);

        $privteKey="-----BEGIN PRIVATE KEY-----\n".wordwrap($privteKey, 64, "\n", true) . "\n-----END PRIVATE KEY-----";

        return openssl_private_decrypt($data,$decrypted,$privteKey);
    }

    //公钥加密
    public static function encrypt($publicKey,$data,&$encrypted) {

        $publicKey = "-----BEGIN PUBLIC KEY-----\n" . wordwrap($publicKey, 64, "\n", true) . "\n-----END PUBLIC KEY-----";

        $success=openssl_public_encrypt($data,$encrypted,$publicKey);
        if ($success) {
            $encrypted=base64_encode($encrypted);
            return true;
        } else {
            return false;
        }
    }

    //创建公钥与私钥
    public static function createRSAKey(&$publicKey,&$privteKey) {
        $config = array(
            "digest_alg" => "sha512",
            "private_key_bits" =>2048,
            "private_key_type" => OPENSSL_KEYTYPE_RSA
        );

        try {
            $result = openssl_pkey_new($config);
            openssl_pkey_export($result, $privteKey, null, $config);
            
            $privteKey=str_replace(["-----BEGIN PRIVATE KEY-----","-----END PRIVATE KEY-----","\n"],"", $privteKey);

            $publicKey = openssl_pkey_get_details($result)['key'];
            $publicKey=str_replace(["-----BEGIN PUBLIC KEY-----","-----END PUBLIC KEY-----","\n"],"", $publicKey);
            
            return true;

        } catch(Exception $e) {
            return false;
        }
    }

}