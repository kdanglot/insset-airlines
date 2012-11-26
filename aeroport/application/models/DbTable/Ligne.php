<?php
class Application_Model_DbTable_Ligne extends Zend_Db_Table_Abstract {

	protected $_name = 'ligne';
	
	public function afficherLesLignes() {
		$db = Zend_Registry::get('db');
		$sql = 'SELECT l.idLigne, duree, typePeriodicite, heureDepart FROM ligne l, trajet t WHERE l.idLigne = t.idLigne';
		$result = $db->fetchAll($sql);
		
		return $result;	
	}
	
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