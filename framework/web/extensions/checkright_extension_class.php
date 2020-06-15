<?php

/**
 * 后台权限验证扩展
 * 
 * @author:sun xian gen(baxgsun@163.com)
 * @class CheckRightExtension
 */
class CheckRightExtension implements Extension
{
	public function before($obj=null)
	{
		$id = $obj->getAction()->getId();
		if($id !='login' && $id !='logout' && isset($obj->needRightActions[$id]))
        {
            $obj->checkRight($id);
        }
	}
	public function after($obj=null)
	{
	}
}
