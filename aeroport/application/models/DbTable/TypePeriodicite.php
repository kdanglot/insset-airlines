<?php
class Application_Model_DbTable_TypePeriodicite extends Zend_Db_Table_Abstract {
	protected $_name = 'typesperiodicite';
	protected $primary = 'TPER_id';
    protected $_dependentTables = 'Application_Model_DbTable_Vol';
}