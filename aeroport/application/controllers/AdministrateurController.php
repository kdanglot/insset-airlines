<?php

class AdministrateurController extends Zend_Controller_Action
{

    public function init() {
    	$auth = Zend_Auth::getInstance();
    	$identity = $auth->getIdentity();
    	//$typeUtilisateur = $identity->UTI_typeEmploye;
    	
    	/*if('administrateur' != $typeUtilisateur) {
    		$this->_redirect('index/index');
    	}*/
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
    }


}

