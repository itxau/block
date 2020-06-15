<?php

/**
 * 验证码Action
 * 
 * @author:sun xian gen(baxgsun@163.com)
 * @class CaptchaAction
 */
class CaptchaAction extends BaseAction
{
	//Action运行入口
	public function run()
	{
		$captcha = new Captcha();
		$captcha->renderImage();
	}
}
?>