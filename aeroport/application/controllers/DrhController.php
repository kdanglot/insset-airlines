<?php
class DrhController extends Zend_Controller_Action {
	
	public function init() {}
	
	public function indexAction() {}
	
	public function ajouterutilisateurAction() {
		$formAjout = new Application_Form_AjouterModifierUtilisateur();
		$this->view->formAjout = $formAjout;
	}
	
	public function modifier_utilisateurAction() {}
	
	public function supprimer_utilisateurAction() {}
	
}
