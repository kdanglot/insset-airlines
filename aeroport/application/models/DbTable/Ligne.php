<?php
class Application_Model_DbTable_Ligne extends Zend_Db_Table_Abstract {

	protected $_name = 'lignes';
	
	public function afficherLesLignes() {
		$db = Zend_Registry::get('db');
		$sql = "SELECT LIG_id FROM lignes";		
		$result = $db->fetchAll($sql);
		
		return $result;	
	} // afficherLigne()
	
	public function ajouterLigne($heureDepart, $duree, $periodicite) {
		$data = array(
				'heureDepart' => $heureDepart,
				'duree' => $duree,
				'typePeriodicite' => $periodicite
		);
		$this->insert($data);
	} // ajouterLigne()
	
	public function modifierLigne($id, $heureDepart, $duree, $periodicite) {
		$data = array(
				'heureDepart' => $heureDepart,
				'duree' => $duree,
				'typePeriodicite' => $periodicite
		);
		$this->update($data, 'id = '. (int)$id);
	} // modifierLigne()
	
	public function supprimerLigne($id) {
		$this->delete('id =' . (int)$id);
	} // supprimerLigne()

} // Applicaion_Model_DbTable_Ligne