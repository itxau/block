<?php
class MobileCodeService
{
    const expire=120;

    public static function send($mobile,$type,&$errMsg) {
        $obj=self::getcode($mobile);
        if ($obj) return true;

        $code = CHash::random('6', 'int');
        $aliSMS=new AliyunSMS($type);
        $response = $aliSMS->sendSms($mobile,["code"=>"$code"]);
        $result=self::object2array($response);
        //$result['Message']="OK";
        //$code="666666";
        if ($result['Message'] =="OK") {
            $data=[
                'mobile'    => $mobile, 
                'code'      => $code, 
                'send_time' => time() + self::expire
            ];
            return (new MobileCodeEntity())->save($data);
        } else {
            $errMsg=$result['Message'];
            return false;
        }
    }

    private static function object2array($array) {  
        if(is_object($array)) {  
            $array = (array)$array;  
        } if(is_array($array)) {  
            foreach($array as $key=>$value) {  
                $array[$key] = self::object2array($value);  
            }  
        }  
        return $array;  
    }  

    public static function verify($mobile,$code,&$errMsg) {
        $obj=self::getcode($mobile);
        $success=false;
        if($obj){
            $success = ($code == $obj['code']);
            if(!$success) $errMsg="验证码错误!";
        }else{
            $errMsg="验证码失效!";
        }
        return $success;
    }

    private static function getcode($mobile) {
        $entity = new MobileCodeEntity();
        $time = time() - self::expire;
        $where="(send_time < $time)";
        $entity->deleteWhere($where);

        $where="(send_time >= $time) and (mobile='$mobile')";
        return $entity->findWhere($where);
    }

   
}
