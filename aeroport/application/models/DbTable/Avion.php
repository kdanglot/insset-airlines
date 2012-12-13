<?php
class Application_Model_DbTable_Avion extends Zend_Db_Table_Abstract {
	protected $_name = 'avions';
	protected $primary = 'AVI_id';
    protected $_dependentTables = 'Application_Model_DbTable_Vol';
}