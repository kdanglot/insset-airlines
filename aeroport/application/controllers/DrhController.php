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
			}
		}
	}
	
	public function modifierpiloteAction() {
		$idPilote = $this->_request->getParam('idPilote');
		$pilote = new Application_Model_DbTable_Pilote();
		$res = $pilote->afficherPilote($idPilote);
		$brevet = new Application_Model_DbTable_Brevets();
		$brevetsByPilotes = $brevet->getBrevetsByPilote($idPilote);
		
		$formModifierPilote = new Application_Form_ModifierPilote();
		
		foreach ($brevetsByPilotes as $b) {
			echo $b['TBRE_id'];
			echo '<br />';
			//var_dump($formModifierPilote->getElement('brevets')->addMultiOption('1', 'test'));
		}
		
		//var_dump($res);
		/*$tab = array(
				'id'=>$id,
				'nom'=>$res['UTI_nom'],
				'prenom'=>$res->UTI_prenom,
				'login'=>$res->UTI_login,
				'email'=>$res->UTI_mail,
				'dateEmbauche'=>$res->UTI_dateEmbauche,
				);*/
		//var_dump($tab);
		/*
		$formModification = new Application_Form_ModifierPilote();*/
		//$formModifierPilote->populate($tab);
		$formModifierPilote->getElement('nom')->setValue($res[0]['utilisateur']['UTI_nom']);
		$formModifierPilote->getElement('prenom')->setValue($res[0]['utilisateur']['UTI_prenom']);		
		$formModifierPilote->getELement('login')->setValue($res[0]['utilisateur']['UTI_login']);
		$formModifierPilote->getELement('email')->setValue($res[0]['utilisateur']['UTI_mail']);
		$formModifierPilote->getElement('dateEmbauche')->setValue($res[0]['utilisateur']['UTI_dateEmbauche']);
		$this->view->formModifierPilote = $formModifierPilote;
		/*$this->view->formModification = $formModification;
		
		if($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			if($formModification->isValid($formData)) {
				$id = $formModification->getValue('id');
				$prenom = $formModification->getValue('prenom');
				$nom = $formModification->getValue('nom');
				$login = $formModification->getValue('login');
				$mdp = 'testMdp';
				$mdp = hash('sha256', $mdp);
				$idBrevets = $formAjout->getValue('typeBrevet');
				$dateEmbauche = $formModification->getValue('dateEmbauche');
				//$dateAjout = date('Y-m-d');
				
				$tabRes = array (
						'UTI_nom'=>$nom,
						'UTI_prenom'=>$prenom,
						'UTI_login'=>$login,
						'UTI_password'=>$mdp,
						'UTI_dateEmbauche'=>$dateEmbauche);
				
				$db = Zend_Registry::get('db');
				$pilote = new Application_Model_DbTable_Pilote();
				$pilote->modifierPilote($id, $tabRes);
			}
		}*/
	}
	
	public function supprimerpiloteAction() {
		$idPilote = $this->_request->getParam('idPilote');
		$pilote = new Application_Model_DbTable_Pilote();
		$res = $pilote->supprimerPilote($idPilote);
		$this->_redirect('/drh/index/');
	}
	
}
