<?php

class GovdeptController extends GovBase
{
    public function init() {
        parent::init();
        $this->entity=new GovDeptEntity();
    }
 
   
}
