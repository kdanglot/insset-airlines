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
	
	public function getAvionDisponible($dateDepartVoulue, $idAeroportDepartVoulue, $idAeroportArriveeVoulue){	
//     	$avions = new Application_Model_DbTable_Avion();
//     	$date = DateTime::createFromFormat('Y-m-d H:i:s', "2013-01-15 01:59:00");
		$avionListe = $this->fetchAll();
		$avionTab = array ();
		$i = 0;
		
// 		Recupperation des aéroports.
		$aeroport = new Application_Model_DbTable_Aeroport();
		$aeroportDepartVoulue = $aeroport->find($idAeroportDepartVoulue)->current();
		$aeroportArriveeVoulue = $aeroport->find($idAeroportArriveeVoulue)->current();
		
// 		Calcul de la distance entre les aeroports;
		$distanceAeroportVoulue = $this->calculDistance($aeroportDepartVoulue->AER_longitude, $aeroportDepartVoulue->AER_latitude, $aeroportArriveeVoulue->AER_longitude, $aeroportArriveeVoulue->AER_latitude);
		
		foreach ($avionListe as $avion){
			//Recuperation du type de l'avion.
			$typeAvion = $avion->findParentApplication_Model_DbTable_TypesAvion();
			
			$volListe = $avion->findApplication_Model_DbTable_Vol();
			$disponible = true;
			foreach ($volListe as $vol){
				//On ne fouille les vols déja effectués.
				if ($vol->VOL_dateDepartEffective == "") {
// 					Creation d'un DateTime basée sur l'heure de depart prévue de l'avion.
					$dateDepartPrevue =  new DateTime($vol->VOL_dateDepartPrevue);
					
// 					Recupperation des aéroports.
					$aeroportDepart = $aeroport->find($vol->AER_id_depart)->current();
					$aeroportArrive = $aeroport->find($vol->AER_id_arrivee)->current();
					
// 					Calcul de la distance entre les aeroports;
					$distanceAeroport = $this->calculDistance($aeroportDepart->AER_longitude, $aeroportDepart->AER_latitude, $aeroportArrive->AER_longitude, $aeroportArrive->AER_latitude);
					
// 					Calcul du temps de trajets entre les aéroports
					$tempsTrajetEnSeconde = $this->calculDuree($distanceAeroport, $typeAvion->TAVI_vitessemoyenne);
					
// 					Calucul de la date d'arrivé de l'avion.
					$dateArriveePrevue = $dateDepartPrevue->add(new DateInterval('PT'.round($tempsTrajetEnSeconde, 0, PHP_ROUND_HALF_DOWN).'S'));
					
// 					Calcul du temps de trajets entre les aéroports
					$tempsTrajetEnSecondeVoulue = $this->calculDuree($distanceAeroportVoulue, $typeAvion->TAVI_vitessemoyenne);
					
// 					Calucul de la date d'arrivé de l'avion.
					$dateDepartVoulueClone = clone $dateDepartVoulue;
					$dateArriveeVoulue = $dateDepartVoulueClone->add(new DateInterval('PT'.round($tempsTrajetEnSecondeVoulue, 0, PHP_ROUND_HALF_DOWN).'S'));
					
					if($dateDepartPrevue < $dateArriveeVoulue  && $dateArriveePrevue > $dateDepartVoulue){
						$disponible = false;
					}
				}
			}
			if ($disponible) {
				$avionTab[$i]["AVI_id"] = $avion->AVI_id;
				$avionTab[$i]["AVI_immatriculation"] = $avion->AVI_immatriculation;
				$avionTab[$i]["TAVI_nom"] = $typeAvion->TAVI_nom;
				$avionTab[$i]["TAVI_nombrePlaces"] = $typeAvion->TAVI_nombrePlaces;
				$i++;
			}
		}
		
		return $avionTab;
	}
	
	public function calculDuree($distance, $vitesseMoyenne){
		return $distance/($vitesseMoyenne/(60*60));
		
	}
	
	public function calculDistance($lon1, $lat1, $lon2, $lat2){
		$R = 6371; // km
		$dLat = deg2rad(($lat2-$lat1));
		$dLon = deg2rad(($lon2-$lon1));
		$lat1 = deg2rad($lat1);
		$lat2 = deg2rad($lat2);
		
		$a = sin($dLat/2) * sin($dLat/2) + sin($dLon/2) * sin($dLon/2) * cos($lat1) * cos($lat2);
		$c = 2 * atan2((sqrt($a)), sqrt(1-$a));
		$d = $R * $c;
		return $d;
	}
	
	public function getAvionsInfoMaintenance() {		
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
	
	public function getTypeAvionById($idAvion) {
		$avion = $this->find($idAvion)->current();
		$typeAvion = $avion->findParentApplication_Model_DbTable_TypesAvion();
		return $typeAvion;
	}
}
