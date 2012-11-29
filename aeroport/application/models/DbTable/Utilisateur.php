<?php

class Application_Model_DbTable_Utilisateur extends Zend_Db_Table_Abstract {
	
	// nom de la de table
	protected $_name = 'utilisateurs';
	protected $primary = 'UTI_id';
    protected $_dependentTables = 'Application_Model_DbTable_Pilote';


	public function typeUtiliateur($idUtilisateur) {
		$db = Zend_Registry::get('db');
		$sql = 'SELECT u.TUTI_id, TUTI_libelle 
				FROM utilisateurs u, typesutilisateurs tu
				WHERE u.TUTI_id = tu.TUTI_id
				AND u.TUTI_id = '.$idUtilisateur.';';
		$res = $db->fetchAll($sql);
		return $res;
	}
} // Applicaion_Model_DbTable_User

