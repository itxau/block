<?php

//事件管理者
class EventManager
{
    private static $obj;
    private static $events;
    private static $eventType;
    private function __construct(){}
    private function __clone(){}

    /**
     * 取得实例
     */
    public static function getInstance()
    {
        if(!self::$obj instanceof self)
        {
            self::$obj=new self();
            $eventsFile = APP_ROOT."protected/config/events.php";
            $eventTypeFile = APP_ROOT."protected/config/event_type.php";
            self::$eventType= is_file($eventTypeFile)?include($eventTypeFile):null;
            self::$events = is_file($eventsFile)?include($eventsFile):null;
        }
        return self::$obj;
    }

    public static function regEvent($type,$objName){
        if(isset(self::$eventType[$type])){

        }
    }
    //发送事件
    function trigger($type,$sender,$data,$callbackName = null)
    {
        if(isset(self::$eventType[$type]) && isset(self::$events[$type])){
            foreach (self::$events[$type] as $eventName => $value) {
                $event = new $eventName();
                $funcName = 'onAction';
                if(method_exists($event,'on'.ucfirst($type))){
                    $funcName = 'on'.ucfirst($type);
                }
                try{
                    $result = $event->$funcName($data);
                    if($callbackName != null){
                        $callbackName($event,$result);
                    }
                }catch(Exception $e){
                    throw new Exception("EventError:{$eventName}->{$funcName} exist error", E_USER_ERROR);
                }
            }
        }
    }
}
