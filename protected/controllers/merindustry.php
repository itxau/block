<?php

class MerindustryController extends AdmBase
{
    public $layout='admin';

    public function init() {
        parent::init();
        $this->entity=new MerindustryEntity();
        
    } 
    
}
