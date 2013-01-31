<?php
class Application_Model_DbTable_Ligne extends Zend_Db_Table_Abstract {

	protected $_name = 'lignes';	
	protected $primary = 'LIG_id';
	
    protected $_dependentTables = array('Application_Model_DbTable_Vol', 'Application_Model_DbTable_Trajet');
    
    protected $_referenceMap = array (
    		'TypePeriodicite' => array(
    				'columns' => array('TPER_id'),
    				'refTableClass' => 'Application_Model_DbTable_TypePeriodicite',
    				'refColumns' => array('TPER_id'),
    				'onUpdate' => self::CASCADE
    		)
    );
	
	public function getLigneById($id){
	
		$id = intval($id);
		
		$db = Zend_Registry::get('db');
		
		$infosLigne = <<<REQUETE
			SELECT LIG_heureDepart heureDepart, LIG_heureArrivee heureArrivee, TPER_id periodicite 
			FROM lignes
			WHERE LIG_id = :id
REQUETE;

		$infosTrajets = <<<REQUETE
			SELECT TRA_ordre, AER_id, AER_nom, VIL_nom, PAY_nom, PAY_id
			FROM lignes
			NATURAL JOIN trajets
			NATURAL JOIN aeroports
			NATURAL JOIN aeroportsappartiennentvilles
			NATURAL JOIN villes
			NATURAL JOIN pays
			WHERE LIG_id = :id
			ORDER BY TRA_ordre ASC
REQUETE;
		
		$getInfosLigne = $db->prepare($infosLigne);
		$getInfosLigne->bindValue('id', $id, PDO::PARAM_INT);
		$getInfosLigne->execute();
		
		// Si rien n'est trouvé
		if ($getInfosLigne->rowCount() == 0) {
            throw new Exception("Impossible de trouver la ligne $id");
        }
		
		$getInfosTrajets = $db->prepare($infosTrajets);
		$getInfosTrajets->bindValue('id', $id, PDO::PARAM_INT);
		$getInfosTrajets->execute();
		
		return array($getInfosLigne->fetch(), $getInfosTrajets->fetchAll());
		//return $getInfosLigne->fetch();
	}
	
	public function afficherLesLignes() {
		$db = Zend_Registry::get('db');
		$sql = "SELECT depart.LIG_id id, idAeroportDepart, nomAeroportDepart, idVilleDepart, nomVilleDepart, 
						idAeroportArrivee, nomAeroportArrivee, idVilleArrivee, nomVilleArrivee
				FROM (
					SELECT AER_id idAeroportArrivee, trajets.LIG_id, AER_nom nomAeroportArrivee,
					 	VIL_nom nomVilleArrivee, VIL_id idVilleArrivee
					FROM trajets
					NATURAL JOIN aeroports
					NATURAL JOIN aeroportsappartiennentvilles
					NATURAL JOIN villes, (
						SELECT (COUNT( * ) - 1) c, LIG_id
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
					WHERE TRA_ordre = '0'
					)depart
					WHERE depart.LIG_id = arrivee.LIG_id
					ORDER BY id";
				
		$result = $db->fetchAll($sql);
		
		return $result;	
	} // afficherLigne()
	
	public function ajouterLigne($heureDepart, $heureDepart, $heureArrivee, $trajets, $periodicite) {
	
		$auth = Zend_Auth::getInstance();
		$identity = $auth->getIdentity();
		
		$bdd = Zend_Registry::get('db');
		
		$infosLigne = <<<REQUETE
			INSERT INTO lignes
			(UTI_id_directionStrategique, LIG_heureDepart, LIG_heureArrivee, LIG_typePeriodicite, LIG_dateAjout)
			VALUES
			(:idUser, :heureDepart, :heureArrivee, :typePeriodicite, :dateAjout)
REQUETE;
		
		$ajouterLigne = $bdd->prepare($infosLigne);
			$ajouterLigne->bindValue('idUser', $identity->UTI_id, PDO::PARAM_INT);
			$ajouterLigne->bindValue('heureDepart', $heureDepart, PDO::PARAM_STR);
			$ajouterLigne->bindValue('heureArrivee', $heureArrivee, PDO::PARAM_STR);
			$ajouterLigne->bindValue('typePeriodicite', $periodicite, PDO::PARAM_STR);
			$ajouterLigne->bindValue('dateAjout', 'NOW()', PDO::PARAM_STR);
		$ajouterLigne->execute();
		
		$infosTrajet = <<<REQUETE
			INSERT INTO trajets
			(LIG_id, AER_id, TRA_ordre)
			VALUES
			(:idLigne, :idAeroport, :ordre)
REQUETE;
		$idAeroport; 
		$ordre = 0;

		$ajouterTrajet = $bdd->prepare($infosTrajet);
			$ajouterTrajet->bindValue('idLigne', $bdd->lastInsertId(), PDO::PARAM_INT);
			$ajouterTrajet->bindParam('idAeroport', $idAeroport, PDO::PARAM_INT);
			$ajouterTrajet->bindParam('ordre', $ordre, PDO::PARAM_INT);
			
		foreach($trajets as $aeroport){
			$idAeroport = $aeroport;
			$ajouterTrajet->execute();
			$ordre ++;
		}
		
	} // ajouterLigne()
	
	public function modifierLigne($id, $heureDepart, $heureArrivee, $aeroportDepart, $aeroportArrive, $periodicite) {
		$dataLigne = array(
				'LIG_heureDepart' => $heureDepart,
				'LIG_heureArrivee' => $heureArrivee,
				'TPER_id' => $periodicite
		);
		$this->update($dataLigne, 'LIG_id = '. (int)$id);
		$dataDepart = array (
				'AER_id' => $aeroportDepart,
				'TRA_ordre' => 0
				);
		
		$dataArrivee = array (
				'AER_id' => $aeroportArrive,
				'TRA_ordre' => 1
				);
		
		$trajet = new Application_Model_DbTable_Trajet();
		$where = $trajet->getAdapter()->quoteInto('LIG_id = ?', $id);
		
		$trajet->update($dataDepart, $where);
		//$trajet->update($dataDepart, 'LIG_id = '.$id);
		//$trajet->update($dataArrivee, 'LIG_id = '.$id);
		
	} // modifierLigne()
	
	public function supprimerLigne($id) {
		$this->delete('LIG_id =' . (int)$id);
	} // supprimerLigne()
	
	public function insertLigne($idUtilisateur, $heureDepart, $heureArrivee, $paysDepart, $aeroportDepart,
			 $paysArrive, $aeroportArrive, $periodicite, $trajets) {
		$dataLigne = array(
			'UTI_id_directionStrategique' => $idUtilisateur,
			'LIG_heureDepart' => $heureDepart,
			'LIG_heureArrivee' => $heureArrivee,
			'TPER_id' => $periodicite,
			'LIG_dateAjout' => 'NOW()'			
		);
		$idLigne = $this->insert($dataLigne);
		$dataDepart = array (
				'LIG_id' => $idLigne,
				'AER_id' => $aeroportDepart, 
				'TRA_ordre' => 0);
		
		$dataArrivee = array (
				'LIG_id' => $idLigne,
				'AER_id' => $aeroportArrive, 
				'TRA_ordre' => 1);		
		
		$trajetTable = new Application_Model_DbTable_Trajet();
		$trajetTable->insert($dataDepart);
		$trajetTable->insert($dataArrivee);
		
		$i = 2;
		foreach ($trajets as $trajet){
			$trajetLigne = $trajetTable->createRow();
			$trajetLigne->LIG_id = $idLigne;	
			$trajetLigne->AER_id = $trajet;
			$trajetLigne->TRA_ordre = $i;
			$trajetLigne->save();
			$i++;
		}
		
	}

} // Applicaion_Model_DbTable_Ligne