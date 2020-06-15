<?php

class TaxdeptController extends TaxBase
{
    public function init() {
        parent::init();
        $this->entity=new DeptEntity();
    }

    public function GetStatus($str) {
    	$vars=get_defined_vars();
    	if (array_key_exists($str, $vars))
    		return $vars[$str];
    	else
    		return 0;
    }
   
}
