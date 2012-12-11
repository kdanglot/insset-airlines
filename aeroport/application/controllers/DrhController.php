<?php
class DrhController extends Zend_Controller_Action {
	
	public function init() {}
	
	public function indexAction() {
		$db = Zend_Registry::get('db');
		$pilote = new Application_Model_DbTable_Pilote();
		$res = $pilote->afficherLesPilotes();		
		$this->view->listePilote = $res;		
	}
	
	public function ajouterutilisateurAction() {
		$formAjout = new Application_Form_AjouterModifierUtilisateur();
		$this->view->formAjout = $formAjout;
	}
	
	public function modifier_utilisateurAction() {}
	
	public function supprimer_utilisateurAction() {}
	
}
