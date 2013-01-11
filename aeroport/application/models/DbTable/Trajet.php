<?php
class Application_Model_DbTable_Trajet extends Zend_Db_Table_Abstract {
	protected $_name = 'trajets';
	protected $_primary = array('LIG_id','AER_id', 'TRA_ordre');
	protected $_referenceMap = array (
			'Ligne' => array(
					'columns' => array('LIG_id'),
					'refTableClass' => 'Application_Model_DbTable_Ligne',
					'refColumns' => array('LIG_id')
					
			),
			'aeroports' => array(
					'columns' => array('AER_id'),
					'refTableClass' => 'Application_Model_DbTable_Aeroport',
					'refColumns' => array('AER_id'),
					'onUpdate' => self::CASCADE
					
			)
	);
}