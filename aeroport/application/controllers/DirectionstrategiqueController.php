<?php
class DirectionstrategiqueController extends Zend_Controller_Action {
	// direction strategique
	public function init() {}

	public function indexAction() {	}
	
	public function ajouterligneAction() {
		$formAjouterLigne = new Application_Form_Ajouterligne();
		$this->view->formAjouterLigne = $formAjouterLigne;
	}
	
	public function modifierligneAction() {}

}