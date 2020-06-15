<?php
/**
 * 管理基类，负责管理控制器的权限、菜单等
 *
 * @author sun xian gen
 */
class EtpBase extends BaseExt
{
	public $layout='enterprise';
	protected $enterprise;
    public $safebox;
	public function init() {
        $this->safebox  = Safebox::getInstance();
        $this->enterprise = $this->safebox->get('enterprise');
        $menu=new EtpMenu();
        $menus = $menu->getMenus();
        $this->assign("menus",$menus);
        $cUrl=$this->currentUrl();
        $this->assign("currentUrl",$cUrl);
        
        if (array_key_exists($cUrl,$menus)) {
            $this->assign("title",$menus[$cUrl]["title"]);
        }
	}

    /**
     * 检测权限
     *
     * @access public
     * @param mixed $actionId
     * @return mixed
     */
    public function checkRight($actionId)  {
        if (in_array($actionId,Constants::NOT_CHECK_RIGHT)) return true;
        $etp=$this->enterprise;
        if (!$etp || !$etp["account"] || !$etp["name"] || !$etp['face_checked'])
            return false;
        else 
            return true;
    }

    public function noRight()  {
       $this->redirect("/enterprise/login");
    }
}
