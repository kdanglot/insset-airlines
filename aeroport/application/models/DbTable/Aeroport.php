<?php
class Application_Model_DbTable_Aeroport extends Zend_Db_Table_Abstract {
	protected $_name = 'aeroports';
	protected $primary = 'AER_id';
    protected $_dependentTables = array('Application_Model_DbTable_Vol', 'Application_Model_DbTable_Trajet');
	
	public function afficherLesAeroports() {
		$db = Zend_Registry::get('db');
		$sql = 'SELECT AER_id, AER_nom FROM aeroports;';
		$result = $db->fetchAll($sql);
		
		return $result;
	}
	
	public function aeroportPays($idPays) {
		$db = Zend_Registry::get('db');
		$sql = 'SELECT a.AER_id, AER_nom 
				FROM aeroports a , pays p, villes v, aeroportsappartiennentvilles av
				WHERE a.AER_id = av.AER_id 
				AND av.VIL_id = v.VIL_id 
				AND v.PAY_id = p.PAY_id 
				AND p.PAY_id ='.$idPays.';';
		return $rows = $db->fetchAll($sql);		
	
	}
}