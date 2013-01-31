<?php
class Application_Model_DbTable_PlacesReservees extends Zend_Db_Table_Abstract {
	protected $_name = 'placesreservees';
	protected $primary = 'PLAR_id';
	
	protected $_referenceMap = array (
			'Reservation' => array(
					'columns' => 'RES_id',
					'refColumns' => 'RES_id',
					'refTableClass' => 'Application_Model_DbTable_Reservation'
			),
			'Places' => array(
					'columns' => 'PLA_id',
					'refColumns' => 'PLA_id',
					'refTableClass' => 'Application_Model_DbTable_Places'
			)
	);
}