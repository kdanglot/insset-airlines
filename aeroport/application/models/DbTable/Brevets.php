<?php
class Application_Model_DbTable_Brevets extends Zend_Db_Table_Abstract {

	protected $_name = 'brevets';
	
	protected $_referenceMap = array (
				'Pilote' => array(
							'columns' => 'PIL_id', 
							'refColumns' => 'PIL_id', 	
							'refTableClass' => 'Application_Model_DbTable_Pilote'
						),
				'TypesBrevet' => array(
							'columns' => 'TBRE_id', 
							'refColumns' => 'TBRE_id', 	
							'refTableClass' => 'Application_Model_DbTable_TypesBrevet'
						)
			);
	
	public function getBrevetsByPilote($idPilote) {
		$select = $this->select()->where('PIL_id ='.$idPilote);
		$rows = $this->fetchAll($select);
		return $rows;
	}
	
	public function updateByPilote($idPilote, array $brevets) {
		$this->delete('PIL_id =' . (int)$idPilote);
		foreach ($brevets as $brevet) {
			$p['PIL_id'] = $brevet['PIL_id'];
			$p['TBRE_id'] = $brevet['TBRE_id'];
			$p['BRE_dateFin'] = $brevet['BRE_dateFin'];
			$this->insert($p);
		}
		//$this->insert($brevets);
	}
	
}