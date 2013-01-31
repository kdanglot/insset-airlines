<?php
class DrhController extends Zend_Controller_Action {
	
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
		$db = Zend_Registry::get('db');
		$pilote = new Application_Model_DbTable_Pilote();
		$res = $pilote->afficherLesPilotes();		
		$this->view->listePilote = $res;		
	}
	
	public function ajouterutilisateurAction() {
		$formAjout = new Application_Form_AjouterPilote();
		$this->view->formAjout = $formAjout;
		
		if($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			if($formAjout->isValid($formData)) {
				$prenom = $formAjout->getValue('prenom');
				$nom = $formAjout->getValue('nom');
				$login = $formAjout->getValue('login');
				$mdp = $formAjout->getValue('mdp');
				$mdp = hash('sha256', $mdp);
				$mail = $formAjout->getValue('email');
				$idBrevets = $formAjout->getValue('typeBrevet');
				$dateEmbauche = $formAjout->getValue('dateEmbauche');
				$dateAjout = date('Y-m-d');

				$db = Zend_Registry::get('db');
				$pilote = new Application_Model_DbTable_Pilote();
				$pilote->ajouterPilote($nom, $prenom, $login, $mdp, $mail, $dateEmbauche, $dateAjout, $idBrevets);
				
				$this->_redirect('drh');
			}
		}
	}
	
	public function modifierpiloteAction() {
		// permet de récupérer l'ID du pilote
		$idPilote = $this->_request->getParam('idPilote');
		// on instancie un objet PILOTE
		$pilote = new Application_Model_DbTable_Pilote();
		// on récupère les infos du pilote
		$infosPilote = $pilote->afficherPilote($idPilote);
		// var_dump($infosPilote);
		// on instancie un objet BREVETS
		$brevet = new Application_Model_DbTable_Brevets();
		// on récupère les brevets d'un pilote
		$brevetsByPilotes = $brevet->getBrevetsByPilote($idPilote);
		// on instancie un objet ModifierPilote => formulaire
		$formModifierPilote = new Application_Form_ModifierPilote();
		
		// tableau contenant les ID des brevets du pilote
		$checked = array();
		foreach ($brevetsByPilotes as $b) {
			$checked[] = $b['TBRE_id'];
		}
		// permet de checker les valeurs
		$formModifierPilote->getElement('brevets')->setValue($checked);
		$formModifierPilote->getElement('nom')->setValue($infosPilote[0]['utilisateur']['UTI_nom']);
		$formModifierPilote->getElement('prenom')->setValue($infosPilote[0]['utilisateur']['UTI_prenom']);		
		$formModifierPilote->getELement('login')->setValue($infosPilote[0]['utilisateur']['UTI_login']);
		$formModifierPilote->getELement('email')->setValue($infosPilote[0]['utilisateur']['UTI_mail']);
		$formModifierPilote->getElement('dateEmbauche')->setValue($infosPilote[0]['utilisateur']['UTI_dateEmbauche']);
		$this->view->formModifierPilote = $formModifierPilote;
				
		if($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			if($formModifierPilote->isValid($formData)) {
				// var_dump($formModifierPilote);
				$prenom = $formModifierPilote->getValue('prenom');
				$nom = $formModifierPilote->getValue('nom');
				$login = $formModifierPilote->getValue('login');
				$brevets = $formModifierPilote->getValue('brevets');
				$dateEmbauche = $formModifierPilote->getValue('dateEmbauche');
				//$dateAjout = date('Y-m-d');
				// var_dump($brevets);
				$tabBrevets = array();
				foreach ($brevets as $b) {
					//var_dump($b);
					$tabBrevets[] = array (
							'PIL_id' => $idPilote,
							'TBRE_id' => $b,
							'BRE_dateFin' => '00'
					);
				}
				
				var_dump($tabBrevets);
				$tabInfos = array (
						'UTI_nom'=>$nom,
						'UTI_prenom'=>$prenom,
						'UTI_login'=>$login,
						'UTI_dateEmbauche'=>$dateEmbauche);
				
				// on modifie les valeurs du pilote
				$res = $pilote->modifierPilote($idPilote, $tabInfos, $tabBrevets);
				
				$this->_redirect('drh');
			}
		}
	}
	
	public function supprimerpiloteAction() {
		$idPilote = $this->_request->getParam('idPilote');
		$pilote = new Application_Model_DbTable_Pilote();
		$res = $pilote->supprimerPilote($idPilote);
		$this->_redirect('/drh/index/');
	}
	
}
