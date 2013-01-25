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
    	
    }


}

