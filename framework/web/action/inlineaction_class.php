<?php

/**
 * 内联action
 * 
 * @author:sun xian gen(baxgsun@163.com)
 * @class InlineAction
 */
class InlineAction extends BaseAction
{
	//Action运行入口
	public function run()
	{
		$controller=$this->getController();
		$methodName=$this->getId();
		$controller->$methodName();
	}
}
?>