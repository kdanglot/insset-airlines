<?php
class Application_Model_DbTable_Pays extends Zend_Db_Table_Abstract {
	
	protected $_name = 'pays';
	
	public function afficherLesPays() {
		$db = Zend_Registry::get('db');
		$req = 'SELECT PAY_id, PAY_nom FROM pays;';
		$result = $db->fetchAll($req);		
		return $result;
	}
	
}