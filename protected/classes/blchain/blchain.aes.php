<?php 
/*
* AES 算法    
*/
class BlcAES {

    private $hex_iv = '00000000000000000000000000000000'; 

    private $key = '1234567890abcdefghijk';

    function __construct($key) {
        $this->key = hash('sha256', $key, true);
    }

    public function filecrypt($contents) {
        try {
            $data = openssl_encrypt(
                $contents, 
                'AES-256-CBC', 
                $this->key, 
                OPENSSL_RAW_DATA, 
                $this->hexToStr($this->hex_iv)
            );
            return base64_encode($data);
        } catch(Exception $e) {
            return false;
        }
       
    }

    public function filedecrypt($contents) {
        try {
            $contents = base64_decode($contents);
            return openssl_decrypt(
                $contents, 
                'AES-256-CBC', 
                $this->key, 
                OPENSSL_RAW_DATA, 
                $this->hexToStr($this->hex_iv)
            );
        } catch(Exception $e) {
            return false;
        }
       
    }

    public function encrypt($input)   {
        $data = openssl_encrypt($input, 'AES-256-CBC', $this->key, OPENSSL_RAW_DATA, $this->hexToStr($this->hex_iv));
        $data = base64_encode($data);
        return $data;
    }
    

    public function decrypt($input)  {
        $decrypted = openssl_decrypt(base64_decode($input), 'AES-256-CBC', $this->key, OPENSSL_RAW_DATA, $this->hexToStr($this->hex_iv));
        return $decrypted;
    }

    function hexToStr($hex)  {
        $string='';
        for ($i=0; $i < strlen($hex)-1; $i+=2){
            $string .= chr(hexdec($hex[$i].$hex[$i+1]));
        }
        return $string;
    }
}