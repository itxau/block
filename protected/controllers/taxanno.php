<?php

class TaxannoController extends TaxBase
{
    public function init() {
        parent::init();
        $this->entity=new AnnounceEntity();
    }
}
