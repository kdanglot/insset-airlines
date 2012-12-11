<?php

class PlanningController extends Zend_Controller_Action
{
    public function init() {
    	$auth = Zend_Auth::getInstance();
    	$identity = $auth->getIdentity();
    	//$typeUtilisateur = $identity->UTI_typeEmploye;
    	
    	/*if('administrateur' != $typeUtilisateur) {
    		$this->_redirect('index/index');
    	}*/
    }

    public function indexAction() {
    	
    }


}

