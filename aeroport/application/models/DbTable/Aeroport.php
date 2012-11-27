<?php
class Application_Model_DbTable_Aeroport extends Zend_Db_Table_Abstract {
	protected $_name = 'aeroports';
	
	public function afficherLesAeroports() {
		$db = Zend_Registry::get('db');
		$sql = 'SELECT AER_id, AER_nom FROM aeroports;';
		$result = $db->fetchAll($sql);
		
		return $result;
	}
}