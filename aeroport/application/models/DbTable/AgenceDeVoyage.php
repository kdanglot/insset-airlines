<?php
class Application_Model_DbTable_AgenceDeVoyage extends Zend_Db_Table_Abstract {
	protected $_name = 'agencesdevoyage';
	protected $primary = 'AGE_id';
	
	protected $_referenceMap = array (
			'AgenceDeVoyage' => array(
					'columns' => 'AGE_id',
					'refColumns' => 'AGE_id',
					'refTableClass' => 'Application_Model_DbTable_AgenceDeVoyage'
			)
	);
	
    protected $_dependentTables = 'Application_Model_DbTable_Reservation';
    
    public function getAgenceDeVoyage(){
    	$agenceDeVoyagesListe = $this->fetchAll();
    	$agenceDeVoyagesTab = array();
    	$i = 0;
    	
    	foreach ($agenceDeVoyagesListe as $agenceDeVoyage) {
    		$agenceDeVoyagesTab[$i]["AGE_id"] = $agenceDeVoyage->AGE_id;
    		$agenceDeVoyagesTab[$i]["AGE_nom"] = $agenceDeVoyage->AGE_nom;
    		$i++;
    	}
    	
    	return $agenceDeVoyagesTab;
    }
}
