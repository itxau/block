<?php

/**
 * widget类
 * 
 * @author:sun xian gen(baxgsun@163.com)
 * @class Widget
 */
class Widget
{
	private $controller;

	public function __construct($controller)
	{
		$this->controller=$controller;
	}
	public function __call($name,$args)
	{
		
	}
}
?>