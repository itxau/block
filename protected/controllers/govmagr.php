<?php

class GovmagrController extends GovBase
{
    public function init() {
        parent::init();
        $this->entity=new GovUserEntity();
    }
 
   
}
