<?php
class DirectionstrategiqueController extends Zend_Controller_Action {
	
	public function init() {
		$auth = Zend_Auth::getInstance();
		$identity = $auth->getIdentity();
		//$typeUtilisateur = $identity->UTI_typeEmploye;
		 
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
				$ligne->ajouterLigne($heureDepart, $heureDepart, $heureArrivee, array($aeroportDepart = '1', $aeroportArrivee = '2'), $periodicite);

				$this->_helper->redirector('index');
			} else {
				$formAjouterLigne->populate($formData);
			}
		}

	}
	
	public function modifierligneAction() {
		
		$form = new Application_Form_AjouterModifierLigne();
		$form->ajouter->setLabel('Modifier');
		$this->view->form = $form;

		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			if ($form->isValid($formData)) {
			
				$id = $form->getValue('id');
				$heureDepart = $formAjouterLigne->getValue('heureDepart');
				$heureArrivee = $formAjouterLigne->getValue('heureArrivee');
				$aeroportDepart = $formAjouterLigne->getValue('aeroportDepart');
				$aeroportArrivee = $formAjouterLigne->getValue('aeroportArrivee');
				$periodicite = $formAjouterLigne->getValue('periodicite');
				
				$ligne = new Application_Model_DbTable_Ligne();
				$ligne->modifierLigne($id, $heureDepart, $heureDepart, $heureArrivee, array($aeroportDepart = '1', $aeroportArrivee = '2'), $periodicite);

				$this->_helper->redirector('index');
			} 
			else {
				$form->populate($formData);
			}
		} 
		else {
			$id = $this->_getParam('id', 0);
			if ($id > 0) {
				$lignes = new Application_Model_DbTable_Ligne();
				$thisLigne = $lignes->getLigneById($id);
				
				// Si on veut enlever les secondes
				$thisLigne['heureDepart'] = substr($thisLigne['heureDepart'], 0, -3);
				$thisLigne['heureArrivee'] = substr($thisLigne['heureArrivee'], 0, -3);
				
				$form->populate($thisLigne);
				
			}
		}
	}
	
	
}
