<?php
class Application_Model_DbTable_Avion extends Zend_Db_Table_Abstract {

	protected $_name = 'avions';
	protected $primary = 'AVI_id';
    protected $_dependentTables = 'Application_Model_DbTable_Vol';
	
	protected $_referenceMap = array (
		'TypeAvion' => array(
			'columns' => 'TAVI_id', 
			'refColumns' => 'TAVI_id', 	
			'refTableClass' => 'Application_Model_DbTable_TypesAvion'
		)
	);
	
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
			$avionTab[] = $avion->AVI_immatriculation;
		}
		
		return $avionTab;
	}
}