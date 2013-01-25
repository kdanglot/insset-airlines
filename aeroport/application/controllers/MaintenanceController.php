<?php

class MaintenanceController extends Zend_Controller_Action
{

    public function init() {
    	$auth = Zend_Auth::getInstance();
    	$identity = $auth->getIdentity();
    	// $typeUtilisateur = $identity->UTI_typeEmploye;
    	
    	// /*if('administrateur' != $typeUtilisateur) {
    		// $this->_redirect('index/index');
    	// }*/
    }

    public function indexAction() {		
		$avion = new Application_Model_DbTable_Avion();
		$avionsDisponibilite = $avion->getAvionsDisponibilite();
		
		foreach ($avionsDisponibilite as $key=>$avion){
			if ($avion["disponibilite"] == "disponible") {
					$avionsDisponibilite[$key]["action"] = "creerMaintenance";
				if ($avion["maintenanceType"] == "petiteMaintenance") {
					$avionsDisponibilite[$key]["actionLabel"] = "Planifier petite maintenance";
				}else if ($avion["maintenanceType"] == "grandeMaintenance") {
					$avionsDisponibilite[$key]["actionLabel"] = "Planifier grande maintenance";
				}else{
					$avionsDisponibilite[$key]["action"] = "disponible";
				}
			}else if ($avion["disponibilite"] == "planifier") {
				$avionsDisponibilite[$key]["action"] = "commencerMaintenance";
				if ($avion["maintenanceType"] == "petiteMaintenance") {
					$avionsDisponibilite[$key]["actionLabel"] = "Commencer petite maintenance";
				}else if ($avion["maintenanceType"] == "grandeMaintenance") {
					$avionsDisponibilite[$key]["actionLabel"] = "Commencer grande maintenance";
				}
			}else if ($avion["disponibilite"] == "encours") {
				$avionsDisponibilite[$key]["action"] = "terminerMaintenance";
				if ($avion["maintenanceType"] == "petiteMaintenance") {
					$avionsDisponibilite[$key]["actionLabel"] = "Terminer petite maintenance";
				}else if ($avion["maintenanceType"] == "grandeMaintenance") {
					$avionsDisponibilite[$key]["actionLabel"] = "Terminer grande maintenance";
				}
			}
		}

		$this->view->avionsDisponibilite = $avionsDisponibilite;
		
		$ajouterMaintenanceForm = new Application_Form_AjouterMaintenance();
		$this->view->ajouterMaintenanceForm = $ajouterMaintenanceForm;
    }

    public function terminerAction() {	
    	$idAvion = $this->getParam("idAvion");
    	$typeMaintenance = $this->getParam("typeMaintenance");
    	$avions = new Application_Model_DbTable_Avion();
    	$avions->terminerMaintenanceActuel($idAvion, $typeMaintenance);
    	
    	$redirector = $this->_helper->redirector;
    	$redirector->goToUrl("maintenance/index");
    }

    public function commencerAction() {	
    	$idAvion = $this->getParam("idAvion");
    	$avions = new Application_Model_DbTable_Avion();
    	$avions->commencerMaintenanceActuel($idAvion);
    	
    	$redirector = $this->_helper->redirector;
    	$redirector->goToUrl("maintenance/index");
    }

    public function creerAction() {	
    	$dateDebutMaintenance = $this->getParam("dateDebutMaintenance");
    	$idAvionMaintenance = $this->getParam("idAvionMaintenance");
    	$typeMaintenance = $this->getParam("typeMaintenance");
    	
    	$auth = Zend_Auth::getInstance();
    	$identity = $auth->getIdentity();
    	$idUtilisateur = $identity->UTI_id;
    	
    	$avions = new Application_Model_DbTable_Avion();
    	$avions->creerMaintenanceActuel($idAvionMaintenance, $dateDebutMaintenance, $typeMaintenance, $idUtilisateur);
    	
    	$redirector = $this->_helper->redirector;
    	$redirector->goToUrl("maintenance/index");
    }

    public function enregistreravionAction() {
    	$flashmessenger = $this->_helper->FlashMessenger;
    	$flashmessenger->addMessage($this->getParam("idAvion"));
    	
    	//Redirection à définir par antoine K
    	$redirector = $this->_helper->redirector;
    	$redirector->goToUrl("maintenance/index");
    }
}

