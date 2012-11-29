<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
     	$acl = new Zend_Acl();
     	$acl->addRole('admin');
     	$acl->addResource('administrateur');
     	$acl->allow('admin','administrateur', 'index');
    } // init()
   
    public function indexAction() {
	
		$formConnexion = new Application_Form_Connexion();
		$this->view->formConnexion = $formConnexion;

		if ($this->getRequest()->isPost()) {
       		$formData = $this->getRequest()->getPost();
        	if ($formConnexion->isValid($formData)) {
            		$login = $formConnexion->getValue('login');
            		$mdp = $formConnexion->getValue('mdp'); 
            		$mdp = hash('SHA256', $mdp);
            		
            		$db = Zend_Registry::get('db');
            		
            		// instanciation de Zend_Auth
            		$auth = Zend_Auth::getInstance();
            		// charger et parametrer l'adapteur
            		// ne pas oublier de coder les mdp
            		           		
            		$dbAdapter = new Zend_Auth_Adapter_DbTable($db, 'utilisateurs', 'UTI_login', 'UTI_password');
            		// charger les crédits (login/mdp) à tester
            		$dbAdapter->setIdentity($login);
            		//$dbAdapter->setCredential($mdp);
            		$dbAdapter->setCredential($mdp);
            		// on teste l'authentification
            		$res = $auth->authenticate($dbAdapter);
          
            		if($res->isValid($formData)) {
            			// on récupère les infos de la personne après authentification
    					$dataUser = $dbAdapter->getResultRowObject(null, 'UTI_password');
    					// on stocke les données dans la session
    					$auth->getStorage()->write($dataUser);
    					// on récupère l'id de l'utilisateur
    					$idTypeUtilisateur = $dataUser->TUTI_id;
    					// on récupère le type de l'utilisateur
    					$utilisateur = new Application_Model_DbTable_Utilisateur();
    					$typeUtilisateur = $utilisateur->typeUtiliateur($idTypeUtilisateur);
    					$typeUtilisateur = $typeUtilisateur[0]['TUTI_libelle'];
    					var_dump($typeUtilisateur);
    					// redirection différente selon le type de l'utiliateur
    					switch ($typeUtilisateur) {
    						case 'administrateur':
    							$this->_redirect('/administrateur/index/');
    						break;
    						case 'drh':
    							$this->_redirect('/drh/index/');
    						break;
    						case 'direction stratégique':
    							$this->_redirect('/directionstrategique/index/');
    						break;
    					}
    	  
            		}
            		else {
            			echo 'Erreur';
            		}
        	} 
		}
       
    } // indexAction()
    
    public function deconnexionAction() {
    	$auth = Zend_Auth::getInstance();
    	$auth->clearIdentity();
    	$this->_redirect('/index/index');
    }
    
    public function testAction() {}
    
}



