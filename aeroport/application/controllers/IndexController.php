<?php

class IndexController extends Zend_Controller_Action
{

    public function init(){
		
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
		
	} // init()
   
    public function indexAction() {

	
		// Si on est déjà connecté transmettre les infos à la vue
		$auth = Zend_Auth::getInstance();
		if($auth->hasIdentity()){
			$this->view->dejaConnecte = $auth->getIdentity()->TUTI_alias;
		}
		
		// Transmettre les messages d'erreur à la vue
		$this->view->error = $this->_getParam('error');
	

	
		// Si on est déjà connecté transmettre les infos à la vue
		$auth = Zend_Auth::getInstance();
		if($auth->hasIdentity()){
			$this->view->dejaConnecte = $auth->getIdentity()->TUTI_alias;
		}
		
		// Transmettre les messages d'erreur à la vue
		$this->view->error = $this->_getParam('error');
	

		$formConnexion = new Application_Form_Connexion();
		$this->view->formConnexion = $formConnexion;

		// Si on a reçu des données
		if ($this->getRequest()->isPost()) {
       		$formData = $this->getRequest()->getPost();
		
			// Si ces données sont valides avec ce formulaire
        	if ($formConnexion->isValid($formData)) {
			
				// Récupérer ces données
				$login = $formConnexion->getValue('login');
				$mdp = hash('SHA256', $formConnexion->getValue('mdp'));
            		
				// Identification avec Adaptater DbTable
				
				$db = Zend_Registry::get('db');
            		
				// Adaptateur
				$authAdapter = new Zend_Auth_Adapter_DbTable($db);
				$authAdapter->setTableName('utilisateurs')
							->setIdentityColumn('UTI_login')
							->setCredentialColumn('UTI_password');
				
				// On entre les paramètres
				$authAdapter->setIdentity($login)
							->setCredential($mdp);
				
				// On récupère le résultat de l'authentification
				$result = $authAdapter->authenticate();
				
				// Si l'authentification est réussie
				if($result->isValid()){
				
					// Récupérer les infos sur l'utilisateur
					$data = $authAdapter->getResultRowObject(null, 'UTI_password');
					
					// on récupère l'alias de l'utilisateur
					$idTypeUtilisateur = $data->TUTI_id;
					$infosUser = new Application_Model_DbTable_TypeUtilisateur();
					$infosUser = $infosUser->getTypeUtilisateur($idTypeUtilisateur);
					$data->TUTI_alias = $infosUser->TUTI_alias;
					$data->TUTI_label = utf8_encode($infosUser->TUTI_label);
					
					$auth->getStorage()->write($data);
					
					if($data->TUTI_alias != "pilote"){
						// Rediriger vers le controlleur adapté
						$this->_redirector->gotoUrl('/' . $infosUser->TUTI_alias . '/index');
					}else{
						$this->_redirector->gotoUrl('index/deconnexion');
					}
					
				}
				else{
				
					// Nettoyer l'identité
					$auth = Zend_Auth::getInstance();
					$auth->clearIdentity();
					
					// Afficher un message de refus
					$this->view->errorMessage = 'Identifiants erronés';
					
				}
        	} 
		}
       
    }
	
	// Déconnexion
    public function deconnexionAction() {
    	
		// Nettoyer l'identité
		$auth = Zend_Auth::getInstance();
		$auth->clearIdentity();
		
		// Détruire les infos de la session
		$session = new Zend_Session_Namespace('role');
		$session->role = 'invite';
		
		// Rediriger vers l'accueil
		$this->_redirector->gotoUrl('/index/index');
		
    }
}
