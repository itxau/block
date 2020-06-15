<?php


//系统主要接口集
//Application接口
interface Application
{
	public function run();
}
//扩展接口
interface Extension
{
	public function before($obj=null);
	public function after($obj=null);
}

//事件接口标准协议
interface EventInterface
{
    public function onAction($data);
}
