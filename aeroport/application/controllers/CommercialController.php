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

    public function terminerreservationAction() {
    	$idReservation = $this->getParam("idReservation");
    	$reservation = new Application_Model_DbTable_Reservation();
    	$reservation->finirReservation($idReservation);
    }
}

