<?php
class Application_Model_DbTable_Ligne extends Zend_Db_Table_Abstract {

	protected $_name = 'lignes';
	
	public function afficherLesLignes() {
		$db = Zend_Registry::get('db');
		$sql = "SELECT idLigne,
(
select CONCAT(GROUP_CONCAT(v.nom SEPARATOR ', '),'/', a.nom ) from aeroport a
join dessert d on d.idAeroport = a.idAeroport
join villes v on v.idVilles= d.idVilles
where a.idAeroport =
(
select t2.idAeroport from trajet t2 where t1.idLigne = t2.idLigne and t2.ordre = MIN(t1.ordre)
) 
) as Depart,
(
select CONCAT(GROUP_CONCAT(v.nom SEPARATOR ', '),'/', a.nom ) from aeroport a
join dessert d on d.idAeroport = a.idAeroport
join villes v on v.idVilles= d.idVilles
where a.idAeroport =
(
select t2.idAeroport from trajet t2 where t1.idLigne = t2.idLigne and t2.ordre = MAX(t1.ordre)
)
) as Arrive
FROM trajet t1
group by idLigne";		
		
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