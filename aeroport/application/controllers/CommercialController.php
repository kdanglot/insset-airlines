<?php

class CommercialController extends Zend_Controller_Action
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

