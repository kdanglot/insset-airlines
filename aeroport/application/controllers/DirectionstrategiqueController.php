<?php
class DirectionstrategiqueController extends Zend_Controller_Action {
	
	public function init() { 
	
		// Mettre en place le redirecteur
		$this->_redirector = $this->_helper->getHelper('Redirector');
		
		// Récupération ACL
		$acl = Zend_Registry::get('acl');
		
		// Récupération du rôle enregistré en session
		$session = new Zend_Session_Namespace('role');
		// var_dump($session->role);exit;
		$role = $session->role;
		$controller = $this->getRequest()->getControllerName();
		$action = $this->getRequest()->getActionName();
		
		// Vérification des droits
		if(!$acl->isAllowed($role, $controller, $action)){
			// Rediriger vers le controlleur adapté
			$this->_redirector->gotoUrl('/index/index/error/Vous devez d\'abord vous connecter');
		}
		
		// Informer le Layout des détails sur le connecté
		Zend_Layout::getMvcInstance()->assign('login', Zend_Auth::getInstance()->getIdentity()->UTI_login);
		Zend_Layout::getMvcInstance()->assign('role', Zend_Auth::getInstance()->getIdentity()->TUTI_alias);
		Zend_Layout::getMvcInstance()->assign('roleLabel', Zend_Auth::getInstance()->getIdentity()->TUTI_label);
	}

	public function indexAction() {
		$lignes = new Application_Model_DbTable_Ligne();
		$this->view->lignes = $lignes->afficherLesLignes();
	}
	
	// améliorer l'affichage des erreurs
	public function ajouterligneAction() {

		$aeroport = new Application_Model_DbTable_Aeroport();
		$formAjouterLigne = new Application_Form_AjouterModifierLigne();
		$this->view->formAjouterLigne = $formAjouterLigne;
		
		$auth = Zend_Auth::getInstance();
		$identity = $auth->getIdentity();
		
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			if ($formAjouterLigne->isValid($formData)) {				
				
				$heureDepart = $formData['heureDepart'];
				$heureArrivee = $formData['heureArrivee'];
				$aeroportDepart = $formData['aeroport-depart'];
				$aeroportArrive = $formData['aeroport-arrive'];
				$periodicite = $formData['periodicite'];
				
				$trajets = array();
				$i = 0;
				while(1){
					if(isset($formData['aeroport-' . $i])){
						$trajets[] = $formData['aeroport-' . $i];
					}
					else{
						break;
					}
					$i++;
				}
				// var_dump($trajets);exit;	
				// $formAjouterLigne->getELement('periodicite')->setValue($periodicite);
				// $formAjouterLigne->getElement('paysDepart')->setValue($paysDepart);
				
				// $list = $aeroport->aeroportPays($paysDepart);
				// foreach ($list as $a) {
					// $formAjouterLigne->getElement('aeroportDepart')->addMultiOption($a['AER_id'], $a['AER_nom']);
				// }
				// $formAjouterLigne->getElement('aeroportDepart')->setValue($aeroportDepart);
				// $formAjouterLigne->getElement('paysArrive')->setValue($paysArrive);
				
				// $list = $aeroport->aeroportPays($paysArrive);
				// foreach ($list as $a) {
					// $formAjouterLigne->getElement('aeroportArrive')->addMultiOption($a['AER_id'], $a['AER_nom']);
				// }
				// $formAjouterLigne->getElement('aeroportArrive')->setValue($aeroportArrive);
				
				// Vérification des listes déroulantes
				if ($aeroportDepart == '-1') {
					$this->view->message = 'Veuillez choisir un aéroport de départ.';
				} else if ($aeroportArrive == '-1') {
					$this->view->message = 'Veuillez choisir un aéroport de d\'arrivée.';
				} else if ($aeroportDepart == $aeroportArrive) {
					$this->view->message = 'Vous avez selectionné le même aéroport.';
				} else {
					$ligne = new Application_Model_DbTable_Ligne();
					$ligne->insertLigne($identity->UTI_id, $heureDepart, $heureArrivee, $aeroportDepart, $aeroportArrive, $periodicite, $trajets);
					$this->_helper->redirector('index');
				}
			} 
		}

	}
	
	public function modifierligneAction() {
		// on récupère l'id de la ligne
		$idLigne = $this->_request->getParam('idLigne');
		
		$ligne = new Application_Model_DbTable_Ligne();
		$infosLigne = $ligne->getLigneById($idLigne);
		//print_r($infosLigne);
		$aeroport = new Application_Model_DbTable_Aeroport();
		// var_dump($infosLigne);exit;
		// affichage du formulaire de modification avec les différentes informations 
		$form = new Application_Form_AjouterModifierLigne();
		$form->ajouter->setLabel('Modifier');
		$form->getElement('heureDepart')->setValue($infosLigne[0]['heureDepart']);
		$form->getElement('heureArrivee')->setValue($infosLigne[0]['heureArrivee']);
		$form->getELement('periodicite')->setValue($infosLigne[0]['periodicite']);
		$form->getElement('paysDepart')->setValue($infosLigne[1][0]['PAY_id']);
		
		$list = $aeroport->aeroportPays($infosLigne[1][0]['PAY_id']);
		foreach ($list as $a) {
			$form->getElement('aeroportDepart')->addMultiOption($a['AER_id'], $a['AER_nom']);
		}
		$form->getElement('aeroportDepart')->setValue($infosLigne[1][0]['AER_id']);
		$form->getElement('paysArrive')->setValue($infosLigne[1][1]['PAY_id']);
		
		$list = $aeroport->aeroportPays($infosLigne[1][1]['PAY_id']);
		foreach ($list as $a) {
			$form->getElement('aeroportArrive')->addMultiOption($a['AER_id'], $a['AER_nom']);
		}
		$form->getElement('aeroportArrive')->setValue($infosLigne[1][1]['AER_id']);
		$this->view->form = $form;
		
		// récupération des valeur + insertion dans la BDD si ok
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			if ($form->isValid($formData)) {
				
				$id = $idLigne;
				$heureDepart = $formData['heureDepart'];
				$heureArrivee = $formData['heureArrivee'];
				$aeroportDepart = $formData['aeroport-depart'];
				$aeroportArrivee = $formData['aeroport-arrive'];
				$periodicite = $formData['periodicite'];
				
				$ligne = new Application_Model_DbTable_Ligne();
				$ligne->modifierLigne($id, $heureDepart, $heureArrivee, $aeroportDepart, $aeroportArrivee, $periodicite);

				// $this->_helper->redirector('index');
			} 
			else {
				$form->populate($formData);
			}
		} 
	}
	
	public function deleteAction() {
		$idLigne = $this->_request->getParam('idLigne');
		
		$ligne = new Application_Model_DbTable_Ligne();
		$res = $ligne->supprimerLigne($idLigne);
		
		$this->_helper->redirector('index');
		
	}
}
