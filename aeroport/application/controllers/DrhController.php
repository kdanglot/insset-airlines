<?php
class DrhController extends Zend_Controller_Action {
	
	public function init() {}
	
	public function indexAction() {
		$db = Zend_Registry::get('db');
		$pilote = new Application_Model_DbTable_Pilote();
		$res = $pilote->afficherLesPilotes();
		var_dump($res);
		
		echo $res[0]['utilisateur']['UTI_nom'];
	}
	
	public function ajouterutilisateurAction() {
		$formAjout = new Application_Form_AjouterModifierUtilisateur();
		$this->view->formAjout = $formAjout;
	}
	
	public function modifier_utilisateurAction() {}
	
	public function supprimer_utilisateurAction() {}
	
}
