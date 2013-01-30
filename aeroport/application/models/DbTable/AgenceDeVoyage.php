<?php
class Application_Model_DbTable_AgenceDeVoyage extends Zend_Db_Table_Abstract {
	protected $_name = 'agencesdevoyage';
	protected $primary = 'AGE_id';
	
    protected $_dependentTables = 'Application_Model_DbTable_Reservation';
}
