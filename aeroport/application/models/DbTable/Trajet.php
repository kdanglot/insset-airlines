<?php
class Application_Model_DbTable_Trajet extends Zend_Db_Table_Abstract {
	protected $_name = 'trajets';
	
	protected $_referenceMap = array (
			'Ligne' => array(
					'columns' => 'LIG_id',
					'refColumns' => 'LIG_id',
					'refTableClass' => 'Application_Model_DbTable_Ligne'
			),
			'aeroports' => array(
					'columns' => 'AER_id',
					'refColumns' => 'AER_id',
					'refTableClass' => 'Application_Model_DbTable_Aeroport'
			)
	);
}