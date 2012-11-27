<?php
class Application_Model_DbTable_Ligne extends Zend_Db_Table_Abstract {

	protected $_name = 'lignes';
	
	public function afficherLesLignes() {
		$db = Zend_Registry::get('db');
		$sql = "SELECT depart.LIG_id, idAeroportDepart, nomAeroportDepart, idVilleDepart, nomVilleDepart, 
						idAeroportArrivee, nomAeroportArrivee, idVilleArrivee, nomVilleArrivee
				FROM (
					SELECT AER_id idAeroportArrivee, trajets.LIG_id, AER_nom nomAeroportArrivee,
					 	VIL_nom nomVilleArrivee, VIL_id idVilleArrivee
					FROM trajets
					NATURAL JOIN aeroports
					NATURAL JOIN aeroportsappartiennentvilles
					NATURAL JOIN villes, (
						SELECT COUNT( * ) c, LIG_id
						FROM trajets
						GROUP BY LIG_id
						)nombre
					WHERE trajets.TRA_ordre = nombre.c
					AND trajets.LIG_id = nombre.LIG_id
					)arrivee, (
					SELECT AER_id idAeroportDepart, LIG_id, AER_nom nomAeroportDepart, VIL_id idVilleDepart, VIL_nom nomVilleDepart
					FROM trajets
					NATURAL JOIN aeroports
					NATURAL JOIN aeroportsappartiennentvilles
					NATURAL JOIN villes
					WHERE TRA_ordre =  '1'
					)depart
					WHERE depart.LIG_id = arrivee.LIG_id";
				
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