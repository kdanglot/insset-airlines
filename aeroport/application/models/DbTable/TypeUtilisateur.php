<?php
class Application_Model_DbTable_TypeUtilisateur extends Zend_Db_Table_Abstract {
	protected $_name = 'typesutilisateurs';
	protected $primary = 'TUTI_id';
    protected $_dependentTables = 'Application_Model_DbTable_Utilisateur';
	
	public function getTypeUtilisateur($id){
		return $this->find($id)->current();
	}
}