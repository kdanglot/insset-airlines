<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        
    } // init()
    

    public function indexAction() {
	
		$formConnexion = new Application_Form_Connexion();
		$this->view->formConnexion = $formConnexion;

		if ($this->getRequest()->isPost()) {
       		$formData = $this->getRequest()->getPost();
        	if ($formConnexion->isValid($formData)) {
            		$login = $formConnexion->getValue('login');
            		$mdp = $formConnexion->getValue('mdp'); 
            		
            		$db = Zend_Registry::get('db');
            		
            		// instanciation de Zend_Auth
            		$auth = Zend_Auth::getInstance();
            		// charger et parametrer l'adapteur
            		// ne pas oublier de coder les mdp
            		$dbAdapter = new Zend_Auth_Adapter_DbTable($db, 'utilisateur', 'login', 'motDePasse');
            		// charger les crédits (login/mdp) à tester
            		$dbAdapter->setIdentity($login);
            		$dbAdapter->setCredential($mdp);
            		// on teste l'authentification
            		$res = $auth->authenticate($dbAdapter);
            		
            		if($res->isValid($formData)) {
            			// on récupère les infos de la personne après authentification
    					$dataUser = $dbAdapter->getResultRowObject(null, 'motDePasse');
    					// on stocke les données dans la session
    					$auth->getStorage()->write($dataUser);
    					// on récupère le type d'utilisateur
    					$typeUser = $dataUser->typeUser;
    					// redirection différente selon le type de l'utiliateur
    					switch ($typeUser) {
    						case 'administrateur':
    							$this->_redirect('/administrateur/index/');
    						break;
    						case 'drh':
    						$this->_redirect('/drh/index/');
    						break;
    					}
    	  
            		}
            		else {
            			echo 'Erreur';
            		}
        	} 
		}
       
    } // indexAction()
    
    public function testAction() {
    	echo 'index/test';
    	$auth = Zend_Auth::getInstance();
		$identity = $auth->getIdentity();
		echo '<br />';
		echo 'Bienvenue ' . $identity->loginUser;
    }
    

   


}



