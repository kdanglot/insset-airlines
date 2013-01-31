<?php

class AdministrateurController extends Zend_Controller_Action
{

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
    	$utilisateur = new Application_Model_DbTable_Utilisateur();
    	$utilisateurCourant = $utilisateur->getUtilisateurCourant();
    	
    	$this->view->utilisateurCourant = $utilisateurCourant;
    }

    public function ajoutAction() {
    	$formUtilisateur = new Application_Form_AjouterModifierUtilisateur();
    	
    	$this->view->formUtilisateur = $formUtilisateur;
    }

    public function creerutilisateurAction() {
    	$utilisateur = new Application_Model_DbTable_Utilisateur();
    	$formUtilisateur = new Application_Form_AjouterModifierUtilisateur();
    	
    	
    	if ($this->getRequest()->isPost()) {
    		$formData = $this->getRequest()->getPost();
    		if ($formUtilisateur->isValid($formData)) {
    			$prenom = $this->getParam("prenom");
    			$nom = $this->getParam("nom");
    			$login = $this->getParam("login");
    			$mail = $this->getParam("mail");
    			$password = $this->getParam("password");
    			$typeUtilisateur = $this->getParam("typeUtilisateur");
    			$dateEmbauche = $this->getParam("dateEmbauche");
    			if (!$utilisateur->loginExistant($login)) {
    				$utilisateur->ajouterUtilisateur($prenom, $nom, $login, $mail, $password, $typeUtilisateur, $dateEmbauche);
    			}			
    		}
    	}
    	
    	$redirector = $this->_helper->redirector;
    	$redirector->goToUrl("administrateur/index");
    }


}

