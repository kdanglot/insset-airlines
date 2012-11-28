<?php
class DirectionstrategiqueController extends Zend_Controller_Action {
	
	public function init() {
		$auth = Zend_Auth::getInstance();
		$identity = $auth->getIdentity();
		$typeUtilisateur = $identity->UTI_typeEmploye;
		 
		/*if(('administrateur' != $typeUtilisateur) || ('directionstrategique' != $typeUtilisateur)) {
			$this->_redirect('index/index');
		}*/
	}

	public function indexAction() {
		$lignes = new Application_Model_DbTable_Ligne();
		$this->view->lignes = $lignes->afficherLesLignes();
		//var_dump($lignes->afficherLesLignes());
		/*$db = Zend_Registry::get('db');
		$dbAdapter = new Zend_Auth_Adapter_DbTable($db, 'ligne', 'heure', 'motDePasse');*/
	}
	
	public function ajouterligneAction() {
		$formAjouterLigne = new Application_Form_AjouterModifierLigne();
		$this->view->formAjouterLigne = $formAjouterLigne;
		
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			
			if ($formAjouterLigne->isValid($formData)) {
			
				$heureDepart = $formAjouterLigne->getValue('heureDepart');
				$heureArrivee = $formAjouterLigne->getValue('heureArrivee');
				$aeroportDepart = $formAjouterLigne->getValue('aeroportDepart');
				$aeroportArrivee = $formAjouterLigne->getValue('aeroportArrivee');
				$periodicite = $formAjouterLigne->getValue('periodicite');
				
				$ligne = new Application_Model_DbTable_Ligne();
				$ligne->ajouterLigne($heureDepart, $heureDepart, $heureArrivee, array($aeroportDepart, $aeroportArrivee), $periodicite);

				$this->_helper->redirector('index');
			} else {
				$formAjouterLigne->populate($formData);
			}
		}

	}
	
	public function modifierligneAction() {
		$ligne = new Application_Model_DbTable_Ligne();
		$this->view->formModifierLigne = $ligne->getLigneById('1');
	}
	
	public function ajaxAction() {
		$this->view->msg = "bonjour";
	}

}
