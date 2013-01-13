<?php
class Application_Model_DbTable_Avion extends Zend_Db_Table_Abstract {

	protected $_name = 'avions';
	protected $primary = 'AVI_id';
    protected $_dependentTables = array('Application_Model_DbTable_Vol', 'Application_Model_DbTable_Maintenances');
	
	protected $_referenceMap = array (
		'TypeAvion' => array(
			'columns' => 'TAVI_id', 
			'refColumns' => 'TAVI_id', 	
			'refTableClass' => 'Application_Model_DbTable_TypesAvion'
		)
	);
	
	public function getAvionsDisponibilite() {		
		$avionListe = $this->fetchAll();
		$avionTab = array ();
		$i = 0;
		
		foreach ($avionListe as $avion) {
			$avionTab[$i]["AVI_id"] = $avion->AVI_id;
			$avionTab[$i]["AVI_immatriculation"] = $avion->AVI_immatriculation;
			$avionTab[$i]["AVI_heureDeVol"] = $avion->AVI_heureDeVol;
			$avionTab[$i]["AVI_heureDeVolsDepuisPetiteMaintenance"] = $avion->AVI_heureDeVolsDepuisPetiteMaintenance;
			$avionTab[$i]["AVI_heureDeVolsDepuisGrandeMaintenance"] = $avion->AVI_heureDeVolsDepuisGrandeMaintenance;
			$typesAvion = $avion->findParentApplication_Model_DbTable_TypesAvion();
			$avionTab[$i]["typesAvion"]["TAVI_id"] = $typesAvion->TAVI_id;
			$avionTab[$i]["typesAvion"]["TAVI_nom"] = $typesAvion->TAVI_nom;
			$avionTab[$i]["typesAvion"]["TAVI_periodicitePetiteMaintenance"] = $typesAvion->TAVI_periodicitePetiteMaintenance;
			$avionTab[$i]["typesAvion"]["TAVI_periodiciteGrandeMaintenance"] = $typesAvion->TAVI_periodiciteGrandeMaintenance;
			$maintenances = $avion->findApplication_Model_DbTable_Maintenances();
			$avionTab[$i]["maintenanceType"] = "";
			$avionTab[$i]["disponibilite"] = "disponible";
			$avionTab[$i]["maintenance"]["dateDebut"] = "";
			foreach ($maintenances as $maintenance){
				if ($maintenance->MAI_dateDebutEffective == "") {
					$avionTab[$i]["disponibilite"] = "planifier";
					$avionTab[$i]["maintenance"]["dateDebut"] = $maintenance->MAI_dateDebutPrevue;
				}else{
					if ($maintenance->MAI_dateFinEffective == "") {
						$avionTab[$i]["disponibilite"] = "encours";
						$avionTab[$i]["maintenance"]["dateDebut"] = $maintenance->MAI_dateDebutEffective;
					}
				}
			}
			if ($avion->AVI_heureDeVolsDepuisPetiteMaintenance >= $typesAvion->TAVI_periodicitePetiteMaintenance) {
				$avionTab[$i]["maintenanceType"] = "petiteMaintenance";
			}
			if ($avion->AVI_heureDeVolsDepuisGrandeMaintenance >= $typesAvion->TAVI_periodiciteGrandeMaintenance) {
				$avionTab[$i]["maintenanceType"] = "grandeMaintenance";
			}
			$i++;
		}
		return $avionTab;
	}
	
	public function afficherLesAvions() {
	
		$avionListe = $this->fetchAll();
		$avionTab = array ();
		$i = 0;
		
		foreach ($avionListe as $avion) {
		
			$avionTab[$i]["AVI_id"] = $avion->AVI_id;
			
			$typeAvion = $avion->findParentApplication_Model_DbTable_TypesAvion();
			$avionTab[$i]["TAVI_id"] = $typeAvion->TAVI_id;	
			$avionTab[$i]["TAVI_nom"] = $typeAvion->TAVI_nom;
			$avionTab[$i]["TBRE_id"] = $typeAvion->TBRE_id;
			$avionTab[$i]["TAVI_nombrePlaces"] = $typeAvion->TAVI_nombrePlaces;
			$avionTab[$i]["TAVI_periodicitePetiteMaintenance"] = $typeAvion->TAVI_periodicitePetiteMaintenance;
			$avionTab[$i]["TAVI_periodiciteGrandeMaintenance"] = $typeAvion->TAVI_periodiciteGrandeMaintenance;
			$avionTab[$i]["TAVI_rayonAction"] = $typeAvion->TAVI_rayonAction;
			$avionTab[$i]["TAVI_distanceAtterrissage"] = $typeAvion->TAVI_distanceAtterrissage;
			
			$i++;
		}
		
		return $avionTab;
	}
	
	public function afficherListeAvionsDisponibles(){
		$avionListe = $this->fetchAll();
		$avionTab = array ();
		
		foreach ($avionListe as $avion) {
			$avionTab[$avion->AVI_id] = $avion->AVI_immatriculation;
		}
		
		return $avionTab;
	}
	
	public function terminerMaintenanceActuel($idAvion, $typeMaintenance){
		$avion = $this->find($idAvion)->current();
		$maintenances = $avion->findApplication_Model_DbTable_Maintenances();
		foreach ($maintenances as $maintenance){
			if ($maintenance->MAI_dateFinEffective == "") {
				$maintenance->MAI_dateFinEffective = date("Y.m.d");
				$maintenance->save();
			}
		}
		if ($typeMaintenance == "Grande") {
			$avion->AVI_heureDeVolsDepuisGrandeMaintenance = 0;
		}
		$avion->AVI_heureDeVolsDepuisPetiteMaintenance = 0;
		$avion->save();
	}
	
	public function commencerMaintenanceActuel($idAvion){
		$avion = $this->find($idAvion)->current();
		$maintenances = $avion->findApplication_Model_DbTable_Maintenances();
		foreach ($maintenances as $maintenance){
			if ($maintenance->MAI_dateDebutEffective == "") {
				$maintenance->MAI_dateDebutEffective = date("Y.m.d");
				$maintenance->save();
			}
		}
	}
	
	public function creerMaintenanceActuel($idAvionMaintenance, $dateDebutMaintenance, $typeMaintenanceLabel, $idUtilisateur){
		$avion = $this->find($idAvionMaintenance)->current();
		
		$maintenances = new Application_Model_DbTable_Maintenances();
		$maintenance = $maintenances->createRow();
		
		$typeMaintenancesTable = new Application_Model_DbTable_TypeMaintenance();
		$typeMaintenances = $typeMaintenancesTable->fetchAll();
		foreach ($typeMaintenances as $typeMaintenance) {
			if ($typeMaintenance->TMAI_typeMaintenance == $typeMaintenanceLabel) {
				$maintenance->TMAI_id = $typeMaintenance->TMAI_id;
			}
		}
		$maintenance->UTI_id_serviceMaintenances = $idUtilisateur;
		$maintenance->AVI_id = $idAvionMaintenance;
		$maintenance->MAI_dateDebutPrevue = $dateDebutMaintenance;
		
		$maintenance->save();
	}
}
