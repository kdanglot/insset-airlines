<?php
class Application_Model_DbTable_Maintenances extends Zend_Db_Table_Abstract {

	protected $_name = 'maintenances';
	protected $primary = 'MAI_id';
	
	protected $_referenceMap = array (
		'Avion' => array(
			'columns' => 'AVI_id', 
			'refColumns' => 'AVI_id', 	
			'refTableClass' => 'Application_Model_DbTable_Avion'
		)
	);
}