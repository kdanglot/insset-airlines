<?php
class DirectionstrategiqueController extends Zend_Controller_Action {
	// direction strategique
	public function init() {}

	public function indexAction() {
		echo 'ds/index';
		echo '<br />';
		$auth = Zend_Auth::getInstance();
		$identity = $auth->getIdentity();
		echo 'Bienvenue ' . $identity->login;	
		
		$formAjouterLigne = new Application_Form_Ajouterligne();
		$this->view->formAjouterLigne = $formAjouterLigne;
		
		
	}
	
	public function ajouterligneAction() {}
	
	public function modifierligneAction() {}

}