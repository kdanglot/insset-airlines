<?php
class Application_Model_DbTable_TypeUtilisateur extends Zend_Db_Table_Abstract {
	protected $_name = 'typesutilisateurs';
	protected $primary = 'TUTI_id';
    protected $_dependentTables = 'Application_Model_DbTable_Utilisateur';
	
	public function getTypeUtilisateur($id){
		return $this->find($id)->current();
	}
	
	public function getTypesUtilisateurs(){
		$typesUtilisateursListe = $this->fetchAll();
		$typesUtilisateursTab = array();
		$i = 0;
		
		foreach ($typesUtilisateursListe as $typeUtilisateur) {
			$typesUtilisateursTab[$i]["TUTI_id"] = $typeUtilisateur->TUTI_id;
			$typesUtilisateursTab[$i]["TUTI_label"] = $typeUtilisateur->TUTI_label;
			$i++;
		}
		
		return $typesUtilisateursTab;
	}
}