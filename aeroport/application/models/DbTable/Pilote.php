<?php
class Application_Model_DbTable_Pilote extends Zend_Db_Table_Abstract {

	protected $_name = 'pilotes';
	protected $primary = 'PIL_id';
	
	protected $_referenceMap = array (
				'Utilisateur' => array(
							'columns' => 'UTI_id', 
							'refColumns' => 'UTI_id', 	
							'refTableClass' => 'Application_Model_DbTable_Utilisateur'
						)
			);
	
	public function afficherLesPilotes() {
		$piloteListe = $this->fetchAll();
		$piloteTab = array ();
		$i = 0;
		
		foreach ($piloteListe as $pilote) {
			$piloteTab[$i]["PIL_id"] = $pilote->PIL_id;
			$piloteTab[$i]["utilisateur"] = $pilote->findParentApplication_Model_DbTable_Utilisateur();
			$i++;
		}
		
		//Pour acceder au donnée de l'utilisateur associcié, il faut acceder au sous tableau de même nom;
		//$piloteTab[0]["utilisateur"]["UTI_nom"];
		
		return $piloteTab;
	}

}