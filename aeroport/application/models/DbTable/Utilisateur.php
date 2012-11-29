<?php

class Application_Model_DbTable_Utilisateur extends Zend_Db_Table_Abstract {
	
	// nom de la de table
	protected $_name = 'utilisateurs';
	protected $primary = 'UTI_id';
    protected $_dependentTables = 'Application_Model_DbTable_Pilote';

} // Applicaion_Model_DbTable_User

