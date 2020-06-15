<?php

/**
 * application类
 *
 * @author:sun xian gen(baxgsun@163.com)
 * @class App
 */
abstract class App extends Module implements Application
{
	//应用名称
	public $name="CoreShop";
	//编码
	public $charset = "UTF-8";
	//语言
	public $language = "en_us";
	//应用的路径
	private $basePath;
	//运行时的路径
	private $runtimePath;
	//运行时的url
	private $runtimeUrl = null;
	//安全码
	private $safeCode='';

    /**
     * 构造函数
     *
     * @access public
     * @param mixed $config
     * @return mixed
     */
	public function __construct($config=null)
	{
		parent::__construct(null,null,$config);
		Core::setApp($this);
		if(is_string($config)) $config = require($config);

			if(!defined('APP_ROOT')) define('APP_ROOT',dirname(dirname(__file__)).DIRECTORY_SEPARATOR);
            $this->setBasePath(APP_ROOT.'protected'.DIRECTORY_SEPARATOR);

			if(isset($config['extConfig']))ExtensionFactory::setExtConfig($config['extConfig']);
			if(isset($config['urlFormat']))Url::setUrlFormat($config['urlFormat']);
			else Url::setUrlFormat('get');
			//应用url路径
			define('APP_URL',Url::baseDir());

			if(isset($config['route']))Url::setRoute($config['route']);
            if(isset($config['name']))$this->name=$config['name'];
			//设置一些基本路径

			if(!defined('APP_URL')) define('APP_URL',Url::baseDir());
			//扩展的目录
			Core::setPath('ext',APP_CODE_ROOT.'extensions');
			//项目的缓存目录
            Core::setPath('cache',APP_ROOT.'cache');
            //项目的数据目录 主要是存放 uploads thumb  database等目录
            $data_path = APP_ROOT.'data/';
            $data_url = APP_URL.'data/';

            Core::setPath('data',$data_path);
            Core::setPath('data_url',$data_url);
            Core::setPath('database',$data_path.'database/');
            Core::setPath('database_url',$data_url.'database/');
            Core::setPath('uploads',$data_path.'uploads/');
            Core::setPath('uploads_url',$data_url.'uploads/');
            Core::setPath('thumb',$data_path.'thumb/');
            Core::setPath('thumb_url',$data_url.'thumb/');
            Core::setPath('theme',APP_ROOT.'themes/default/');
            Core::setPath('headpic',$data_path.'headpic/');
            Core::setPath('headpic_url',$data_url.'headpic/');


            //配制一些要引用的文件
			//加载用户自己扩展的class类，默认为用户项目下的classes目录
			if(isset($config['classes']))Core::setClasses($config['classes']);
			else Core::setClasses('classes.*');
			if(isset($config['imports']))Core::setImports($config['imports']);
			//设置安全码
			if(isset($config['safeCode']))$this->safeCode=$config['safeCode'];
			//加载主题
			if(isset($config['theme'])){
                Core::app()->setTheme($config['theme']);
                Core::setPath('theme',APP_ROOT.'themes/'.$config['theme'].'/');
            }
			//加载主题下的皮肤
			if(isset($config['skin']))Core::app()->setSkin($config['skin']);

			//用户项目里的错误处理的controller类
			if(isset($config['errorsController']))Core::setErrorsController($config['errorsController']);
			else Core::setErrorsController("Error");
			//用户自定义的调试模式
			if(isset($config['debug']))Core::setDebug($config['debug']);

			if(isset($config['timezone']))
			{
				$timezone = $config['timezone'];
				date_default_timezone_set($timezone);
			}
			unset($config);
	}
    /**
     * 虚函数doRequest，让子类来实现
     *
     * @access public
     * @return mixed
     */
	abstract public function doRequest();
    /**
     * 应用启动运行的函数
     *
     * @access public
     * @return mixed
     */
	public function run()
	{
		//实现对Application的扩展
		Core::$_iserror = true;
		$appExtension = ExtensionFactory::getFactory('appExtension');
		if($appExtension !== null )
		{
			$appExtension->before();
			$this->doRequest();
			$appExtension->after();
		}
		else $this->doRequest();
		Core::$_iserror = false;
	}

    /**
     * 取得安全码
     *
     * @access public
     * @return String
     */
	public function getSafeCode()
	{
		return $this->safeCode;
	}
    /**
     * 设置应用的基本路径
     *
     * @access public
     * @param mixed $basePath
     * @return mixed
     */
	public function setBasePath($basePath)
	{
		$this->basePath = $basePath;
	}
    /**
     * 取得应用的基本路径
     *
     * @access public
     * @return String
     */
	public function getBasePath()
	{
		return $this->basePath;
	}
    /**
     * 取得编译后的URL路径
     *
     * @access public
     * @return mixed
     */
	public function getRuntimeUrl()
	{
		if($this->runtimeUrl === null) $this->runtimeUrl = APP_URL.'runtime';
		 return $this->runtimeUrl;
	}
    /**
     *  取得编译后的运行路径
     *
     * @access public
     * @return mixed
     */
	public function getRuntimePath()
	{
		if($this->runtimePath!==null) return $this->runtimePath;
		else
		{
			if(($theme = Core::app()->getTheme())!==null) $this->setRuntimePath('runtime'.DIRECTORY_SEPARATOR.$theme->getName());
			else
				$this->setRuntimePath('runtime');
			return $this->runtimePath;
		}
	}
    /**
     * 设置编译行路径
     *
     * @access public
     * @param mixed $path
     * @return mixed
     */
	public function setRuntimePath($path)
	{
		$this->runtimePath = APP_ROOT.$path;
	}
	public function end()
	{
		exit();
	}
}
