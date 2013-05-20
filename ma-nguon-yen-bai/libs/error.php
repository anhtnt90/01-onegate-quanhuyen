<?php
/**
 * @copyright	Copyright (C) 2012 Tam Viet Tech. All rights reserved.
 * 
 * @author		Ngo Duc Lien <liennd@gmail.com>
 * @author		Luong Thanh Binh <ltbinh@gmail.com>
 */
class Error extends Controller {

    function __construct() {
        parent::__construct('cores','error');        
        $this->view->msg = "This page doesnt exist!";
    }
    
    public function main(){
        echo $this->view->msg;
    }

}