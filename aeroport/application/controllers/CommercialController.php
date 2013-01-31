<?php

class CommercialController extends Zend_Controller_Action
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
    }

    public function indexAction() {
    	$reservationTable = new Application_Model_DbTable_Reservation();
    	$reservations = $reservationTable->getReservations();
    	
    	$this->view->reservations = $reservations;
    }

    public function reserverAction() {
    	$idVol = $this->getParam("idVol");
    	$placeTable = new Application_Model_DbTable_Places();
    	$nbPlaces = $placeTable->countPlacesDispoByVol($idVol);
    	
    	$formPlace = new Application_Form_ReserverPlace();
    	
    	$this->view->idVol = $idVol; 
    	$this->view->formPlace = $formPlace;
    	$this->view->nbPlaces = $nbPlaces;
    }

    public function terminerreservationAction() {
    	$idReservation = $this->getParam("idReservation");
    	$reservation = new Application_Model_DbTable_Reservation();
    	$idVol = $reservation->finirReservation($idReservation);
    	
    	$redirector = $this->_helper->redirector;
    	$redirector->goToUrl("commercial/placesvol/idVol/".$idVol);
    }
    
    public function lancerreservationAction(){
    	$idAgenceDeVoyage = $this->getParam("agenceDeVoyage");
    	$nbPlacesVoulu = $this->getParam("nbPlacesVouluInput");
    	$idVol = $this->getParam("idVol");
    	
    	$reservationTable = new Application_Model_DbTable_Reservation();
    	$reservationTable->ajouterReservation($idAgenceDeVoyage, $nbPlacesVoulu, $idVol);
    	
    	$redirector = $this->_helper->redirector;
    	$redirector->goToUrl("commercial/index");
    }
}

