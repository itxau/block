<?php return array (
  'classes' => 
        array (
          0 => 'classes.*',
          1 => 'extensions.*',
          2 => 'classes.payments.*',
        ),
  'theme' => 'default',
  'urlFormat' => 'get',
  'db' => 
          array (
            'type' => 'mysql',
            'tablePre' => '',
			'host' => '127.0.0.1',
            'port' => '3306',
            'user' => 'root',
            'password' => '123456',
			'name'=>'qhtaxdb',
          ),
  'route' => 
          array (
           
          ),
  'extConfig' => 
          array (
            'controllerExtension' => 
            array (
              0 => 'ControllerExt',
            ),
          ),
);
?>