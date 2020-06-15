<?php

ini_set("display_errors", "on");

//print_r(dirname(__DIR__));die();

require_once dirname(__DIR__) . '/aliyunsms/vendor/autoload.php';

use Aliyun\Core\Config;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use Aliyun\Api\Sms\Request\V20170525\QuerySendDetailsRequest;

// 加载区域结点配置
Config::load();

class AliyunSMS
{

    private $templateCode="";

    private function InitTemplateCode($topictype) {
        switch ($topictype) {
            case 'info': //信息变更
                $this->templateCode="SMS_181276299";
                break;
            case 'passwd': //修改密码
                $this->templateCode="SMS_181276300";
                break;
            case 'reg':  //用户注册
                $this->templateCode="SMS_181276301";
                break;
            case 'unusual': //登录异常
                $this->templateCode="SMS_181276302";
                break;
            case 'login': //登录确认
                $this->templateCode="SMS_181276303";
                break;
            case 'check': //身份验证
                $this->templateCode="SMS_181276304";
                break;
        }
    }

    
    public function __construct($topictype)
    {
        $this->InitTemplateCode($topictype);
        // 短信API产品名
        $product = "Dysmsapi";

        // 短信API产品域名
        $domain = "dysmsapi.aliyuncs.com";

        // 暂时不支持多Region
        $region = "cn-shenzhen";

        // 服务结点
        $endPointName = "cn-shenzhen";

        // 初始化用户Profile实例
        $profile = DefaultProfile::getProfile(
            $region, 
            AliyunConf::ACCESSKEY, 
            AliyunConf::ACCESSSECRET
        );

        // 增加服务结点
        DefaultProfile::addEndpoint($endPointName, $region, $product, $domain);

        // 初始化AcsClient用于发起请求
        $this->acsClient = new DefaultAcsClient($profile);
       
    }

  
    public function sendSms($phoneNumbers, $templateParam = null)
    {
        if (!$this->templateCode) {
            return array('Message'=>'信息类型不正确');
        }
        $request = new SendSmsRequest();

        // 必填，设置雉短信接收号码
        $request->setPhoneNumbers($phoneNumbers);

        // 必填，设置签名名称
        $request->setSignName(AliyunConf::SIGN);

        // 必填，设置模板CODE
        $request->setTemplateCode($this->templateCode);

        // 可选，设置模板参数
        if($templateParam) {
            $request->setTemplateParam(json_encode($templateParam));
        }

        // 可选，设置流水号
        //if($this->outId) {
            $request->setOutId(AliyunConf::OUTID);
        //}

        // 发起访问请求
        $acsResponse = $this->acsClient->getAcsResponse($request);

        // 打印请求结果
        // var_dump($acsResponse);

        return $acsResponse;
    
    }

   
}
