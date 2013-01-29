<?php
class Application_Model_DbTable_TypeIncident extends Zend_Db_Table_Abstract {
	protected $_name = 'typesIncident';
	protected $primary = 'TINC_id';
	
	public function getTypeIncident() {
		$db = Zend_Registry::get('db');
		$sql = 'SELECT * FROM typesincident;';
		$result = $db->fetchAll($sql);		
		return $result;
	}
}