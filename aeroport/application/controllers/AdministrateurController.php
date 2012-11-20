<?php

class AdministrateurController extends Zend_Controller_Action
{

    public function init() {
        /* Initialize action controller here */
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

