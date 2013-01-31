<?php
class Application_Model_DbTable_Places extends Zend_Db_Table_Abstract {
	protected $_name = 'places';
	protected $primary = 'PLA_id';
	
    protected $_dependentTables = 'Application_Model_DbTable_PlacesReservees';
	
	protected $_referenceMap = array (
			'Vol' => array(
					'columns' => 'VOL_id',
					'refColumns' => 'VOL_id',
					'refTableClass' => 'Application_Model_DbTable_Vol'
			)
	);
    
    public function countPlacesDispoByVol($idVol) {
    	return count($this->fetchAll($this->select()->from("places")->where("VOL_id = ?", $idVol)->where("PLA_statut = 0")));
    }
}
