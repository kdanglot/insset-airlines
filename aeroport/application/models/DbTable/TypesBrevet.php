<?php
class Application_Model_DbTable_TypesBrevet extends Zend_Db_Table_Abstract {

	protected $_name = 'typesbrevet';
	protected $primary = 'TBRE_id';
    protected $_dependentTables = 'Application_Model_DbTable_Brevets';
    
    public function afficherLesBrevets() {
    	$db = Zend_Registry::get('db');
    	$req = 'SELECT TBRE_id, TBRE_nom FROM typesbrevet;';
    	$result = $db->fetchAll($req);
    	return $result;
    }

}