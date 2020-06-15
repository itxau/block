<?php
/**
 * 事件处理类
 * 
 * @author:sun xian gen(baxgsun@163.com)
 * @class Event
 */
class Event
{
	public $sender;

	public $handled = false;

	public function __construct($sender)
	{
		$this->sender = $sender;
	}
}
