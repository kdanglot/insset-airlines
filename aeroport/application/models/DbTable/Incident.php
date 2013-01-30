<?php
class Application_Model_DbTable_Incident extends Zend_Db_Table_Abstract {
	protected $_name = 'incidents';
	protected $primary = 'INC_id';

	public function getIncidents() {
		return $this->fetchAll();
	}
	public function insertIncident(array $data) {
		return $this->insert($data);
	}
}