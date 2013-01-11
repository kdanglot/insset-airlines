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
				if ($avion["maintenanceType"] == "petiteMaintenance") {
					$avionsDisponibilite[$key]["action"] = "creerPetiteMaintenance";
					$avionsDisponibilite[$key]["actionLabel"] = "Planifier petitte maintenance";
				}else if ($avion["maintenanceType"] == "grandeMaintenance") {
					$avionsDisponibilite[$key]["action"] = "creerGrandeMaintenance";
					$avionsDisponibilite[$key]["actionLabel"] = "Planifier grande maintenance";
				}else{
					$avionsDisponibilite[$key]["action"] = "disponible";
				}
			}else if ($avion["disponibilite"] == "planifier") {
				if ($avion["maintenanceType"] == "petiteMaintenance") {
					$avionsDisponibilite[$key]["action"] = "commencerPetiteMaintenance";
					$avionsDisponibilite[$key]["actionLabel"] = "Commencer petitte maintenance";
				}else if ($avion["maintenanceType"] == "grandeMaintenance") {
					$avionsDisponibilite[$key]["action"] = "commencerGrandeMaintenance";
					$avionsDisponibilite[$key]["actionLabel"] = "Commencer grande maintenance";
				}
			}else if ($avion["disponibilite"] == "encours") {
				$avionsDisponibilite[$key]["action"] = "terminerMaintenance";
				if ($avion["maintenanceType"] == "petiteMaintenance") {
					$avionsDisponibilite[$key]["actionLabel"] = "Terminer petitte maintenance";
				}else if ($avion["maintenanceType"] == "grandeMaintenance") {
					$avionsDisponibilite[$key]["actionLabel"] = "Terminer grande maintenance";
				}
			}
		}

		$this->view->avionsDisponibilite = $avionsDisponibilite;
    }

    public function terminerAction() {		
    	
    }
}

