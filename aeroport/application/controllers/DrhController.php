<?php
class DrhController extends Zend_Controller_Action {
	
	public function indexAction() {
		$auth = Zend_Auth::getInstance();
		$identity = $auth->getIdentity();
		echo 'Bienvenue ' . $identity->UTI_login;
	}
	
}
