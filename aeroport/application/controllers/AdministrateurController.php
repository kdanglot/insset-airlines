<?php

class AdministrateurController extends Zend_Controller_Action
{

    public function init() {
    	$auth = Zend_Auth::getInstance();
    	$identity = $auth->getIdentity();
    	$typeUtilisateur = $identity->typeEmploye;
    	
    	if('administrateur' != $typeUtilisateur) {
    		$this->_redirect('index/index');
    	}
    }

    public function indexAction() {
        // action body
        echo 'administrateur/index';
        echo '<br />';
    	$auth = Zend_Auth::getInstance();
		$identity = $auth->getIdentity();		
		echo 'Bienvenue ' . $identity->login;
    }


}

