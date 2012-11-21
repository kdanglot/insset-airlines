<?php
class DirectionstrategiqueController extends Zend_Controller_Action {
	// direction strategique
	public function init() {}

	public function indexAction() {
		$lignes = new Application_Model_DbTable_Ligne();
		$this->view->lignes = $lignes->fetchAll();
		/*$db = Zend_Registry::get('db');
		$dbAdapter = new Zend_Auth_Adapter_DbTable($db, 'ligne', 'heure', 'motDePasse');*/
	}
	
	public function ajouterligneAction() {
		$formAjouterLigne = new Application_Form_Ajouterligne();
		$this->view->formAjouterLigne = $formAjouterLigne;
	}
	
	public function modifierligneAction() {
		$formModifierLigne = new Application_Form_Modifierligne();
		$this->view->formModifierLigne = $formModifierLigne;
	}

}