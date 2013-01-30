<?php
class Application_Model_DbTable_Incident extends Zend_Db_Table_Abstract {
	protected $_name = 'incidents';
	protected $primary = 'INC_id';

	// permet de récupère l'adapter
	public function getDbAdapter() {
		return Zend_Registry::get('db');
	}
	
	// permet de récupère tous les incidents
	public function getIncidents() {
		return $this->fetchAll();
	}
	
	// permet de récupère tous les incidents d'un vol
	public function getIncidentByIdVol($idVol) {
		$sql = 'SELECT * 
				FROM incidents i, typesincident ti 
				WHERE VOL_id ='.$idVol.'
				AND i.TINC_id = ti.TINC_id';
		return $this->getDbAdapter()->fetchAll($sql);
	}
	
	// permet d'ajouter un incident
	public function insertIncident(array $data) {
		return $this->insert($data);
	}
}