<?php
class Application_Model_DbTable_Vol extends Zend_Db_Table_Abstract {
	protected $_name = 'vols';
	protected $primary = 'VOL_id';
	
    protected $_dependentTables = 'Application_Model_DbTable_Trajet';
	
	protected $_referenceMap = array (
			'Ligne' => array(
					'columns' => 'LIG_id',
					'refColumns' => 'LIG_id',
					'refTableClass' => 'Application_Model_DbTable_Ligne'
			),
			'Avion' => array(
					'columns' => 'AVI_id',
					'refColumns' => 'AVI_id',
					'refTableClass' => 'Application_Model_DbTable_Avion'
			)
	);
	
	// Récupérer la date de départ la plus ancienne
	public function getFirstDateDepart(){
		$premierVol = $this->fetchRow($this->select()->from($this->_name, array('MIN(VOL_dateDepartPrevue) AS date')));
		return $premierVol->date;
	}
	
	// $date : objet DateTime 
	// Format de $nbSemaine : int
	public function afficherVolPlanning($date, $nbSemaine){
// 		Recuperation des lignes.
		$tabLignes = new Application_Model_DbTable_Ligne();
		$lignes = $tabLignes->fetchAll();
		
		// Travailler sur une copie de l'objet
		$date = clone $date;
		
		//Création du calendrier des vols
		$calendrierVol = array();
		
		//On fouille les lignes
		foreach ($lignes as $ligne){
			//On retrouve la periodicité de la ligne.
			$periodicite = $ligne->findParentApplication_Model_DbTable_TypePeriodicite();
			
			//On retrouve les vols de la ligne.
			$vols = $ligne->findApplication_Model_DbTable_Vol();
			
			//On retrouve la date du début de la semaine actuel.
			$dateJour = $date->sub(new DateInterval('P'.($date->format('N')-1).'D'));
			
			//Si, on a affaire a une ligne journalliére.
			if ($periodicite->TPER_label == "journalliers") {
				
				
// 				Boucle pour les 35 jours (5 semaines).
				for ($i = 0; $i < $nbSemaine*7; $i++) {
					
					//Creation des lignes devant décollé le jours en question.
					$calendrierVol[$i][$ligne->LIG_id] = $this->remplirTrajetCalendrier($ligne);
					
					//On fouille les vols de la ligne.
					foreach ($vols as $vol){

// 						Mise en forme de la date de debut du vol
						// if($vol->VOL_dateDepartEffective==""){
							$dateDepart = DateTime::createFromFormat('Y-m-d H:i:s', $vol->VOL_dateDepartPrevue);
						// }else{
							// $dateDepart = DateTime::createFromFormat('Y-m-d H:i:s', $vol->VOL_dateDepartEffective);
						// }

// 						Comparaison avec le jour en question.
						if($dateJour->format('Y-m-d') == $dateDepart->format('Y-m-d')){
							
							//On fouille les trajets du vol.
							for ($j = 0; $j < count($calendrierVol[$i][$ligne->LIG_id]); $j++) {
								
								//Si le trajet correspond.
								if ($calendrierVol[$i][$ligne->LIG_id][$j]["aeroportDepart"]["AER_id_depart"] == $vol->AER_id_depart) {
									
									//On ajoute le vol à la ligne voulu.
									$calendrierVol[$i][$ligne->LIG_id][$j] = $this->remplirVolTab($vol);
								}
							}
						}
					}
					
					$dateJour = $dateJour->add(new DateInterval('P1D'));
				}
			}else if ($periodicite->TPER_label == "hebdomadaire") {//Si, on a affaire a une ligne hebdomadaire.
// 				Boucle pour les 5 semaines.
				for ($i = 0; $i < $nbSemaine; $i++) {	
						
					//Creation des lignes devant décollé le jour et la semaine en question.
					$calendrierVol[($i*7)+($periodicite->TPER_info-1)][$ligne->LIG_id] = $this->remplirTrajetCalendrier($ligne);

					//On fouille les vols de la ligne.
					foreach ($vols as $vol){
						
// 						Mise en forme de la date de debut du vol.
						if($vol->VOL_dateDepartEffective==""){
							$dateDepart = DateTime::createFromFormat('Y-m-d H:i:s', $vol->VOL_dateDepartPrevue);
						}else{
							$dateDepart = DateTime::createFromFormat('Y-m-d H:i:s', $vol->VOL_dateDepartEffective);
						}
						
// 						Comparaison avec la semaine en question.
						if($dateJour->format('W') == $dateDepart->format('W') && $dateJour->format('Y') == $dateDepart->format('Y')){
							
							//On fouille les trajets du vol.
							for ($j = 0; $j < count($calendrierVol[($i*7)+($periodicite->TPER_info-1)][$ligne->LIG_id]); $j++) {
								
								//Si le trajet correspond.
								if ($calendrierVol[($i*7)+($periodicite->TPER_info-1)][$ligne->LIG_id][$j]["aeroportDepart"]["AER_id_depart"] == $vol->AER_id_depart) {
									
									//On ajoute le vol à la ligne voulu.
									$calendrierVol[($i*7)+($periodicite->TPER_info-1)][$ligne->LIG_id][$j] = $this->remplirVolTab($vol);
								}
							}
						}
					}
					
					//on ajoute 7 jours.
					$dateJour = $dateJour->add(new DateInterval('P7D'));
				}
			}
		}
		
		return $calendrierVol;
	}
	
	public function remplirTrajetCalendrier($ligne){
			
		//On retrouve les trajets de la ligne.
		$trajets = new Application_Model_DbTable_Trajet();
		$trajets = $trajets->fetchAll("LIG_id = ".$ligne->LIG_id, "TRA_ordre");
		
		$j=0;
			
		$aeroport = new Application_Model_DbTable_Aeroport();
		$ligneCalendrier = array();
		foreach ($trajets as $trajet){
			if ($j==0) {
				$ligneCalendrier[$trajet->TRA_ordre]["aeroportDepart"]["AER_id_depart"] = $trajet->AER_id;
				$ligneCalendrier[$trajet->TRA_ordre]["aeroportDepart"]["AER_nom"] = $aeroport->find($trajet->AER_id)->current()->AER_nom;
			}else {
				if ($j == (count($trajets)-1)) {
					$ligneCalendrier[$exTrajet->TRA_ordre]["aeroportArrivee"]["AER_id_arrivee"] = $trajet->AER_id;
					$ligneCalendrier[$exTrajet->TRA_ordre]["aeroportArrivee"]["AER_nom"] = $aeroport->find($trajet->AER_id)->current()->AER_nom;
				}else{
					$ligneCalendrier[$trajet->TRA_ordre]["aeroportDepart"]["AER_id_depart"] = $trajet->AER_id;
					$ligneCalendrier[$trajet->TRA_ordre]["aeroportDepart"]["AER_nom"] = $aeroport->find($trajet->AER_id)->current()->AER_nom;
		
					$ligneCalendrier[$exTrajet->TRA_ordre]["aeroportArrivee"]["AER_id_arrivee"] = $trajet->AER_id;
					$ligneCalendrier[$exTrajet->TRA_ordre]["aeroportArrivee"]["AER_nom"] = $aeroport->find($trajet->AER_id)->current()->AER_nom;
				}
			}
		
			$exTrajet = $trajet;
			$j++;
		}
		
		return $ligneCalendrier;
	}
	
	public function remplirVolTab($vol){
	
		$volTab["VOL_id"] = $vol->VOL_id;
		$volTab['LIG_id'] = $vol->LIG_id;
		$aeroport = new Application_Model_DbTable_Aeroport();
		$volTab["aeroportDepart"]["AER_id_depart"] = $vol->AER_id_depart;
		$volTab["aeroportDepart"]["AER_nom"] = $aeroport->find($vol->AER_id_depart)->current()->AER_nom;
		$volTab["aeroportArrivee"]["AER_id_arrivee"] = $vol->AER_id_arrivee;
		$volTab["aeroportArrivee"]["AER_nom"] = $aeroport->find($vol->AER_id_arrivee)->current()->AER_nom;
		
		$avion = $vol->findParentApplication_Model_DbTable_Avion();
		$volTab["avion"]["AVI_id"] = $avion->AVI_id;
		$volTab["avion"]["AVI_immatriculation"] = $avion->AVI_immatriculation;
		
		$pilotes = new Application_Model_DbTable_Pilote();
		$volTab["pilote"]["PIL_id"] = $vol->PIL_id;
		$pilote = $pilotes->find($vol->PIL_id)->current();
			$utilisateur = $pilote->findParentApplication_Model_DbTable_Utilisateur();
			$volTab["pilote"]["utilisateur"]["UTI_id"] = $utilisateur->UTI_id;
			$volTab["pilote"]["utilisateur"]["UTI_nom"] = $utilisateur->UTI_nom;
			$volTab["pilote"]["utilisateur"]["UTI_prenom"] = $utilisateur->UTI_prenom;
			$volTab["pilote"]["utilisateur"]["UTI_mail"] = $utilisateur->UTI_mail;  
		$volTab["coPilote"]["PIL_id"] = $vol->PIL_id_copilote;
		$pilote = $pilotes->find($vol->PIL_id_copilote)->current();
			$utilisateur = $pilote->findParentApplication_Model_DbTable_Utilisateur();
			$volTab["coPilote"]["utilisateur"]["idUtilisateur"] = $utilisateur->UTI_id;
			$volTab["coPilote"]["utilisateur"]["UTI_nom"] = $utilisateur->UTI_nom;
			$volTab["coPilote"]["utilisateur"]["UTI_prenom"] = $utilisateur->UTI_prenom;
			$volTab["coPilote"]["utilisateur"]["UTI_mail"] = $utilisateur->UTI_mail;  
			
		$volTab["VOL_dateDepartPrevue"] = $vol->VOL_dateDepartPrevue;
		$volTab["VOL_dateDepartEffective"] = $vol->VOL_dateDepartEffective;
		$volTab["VOL_dateArriveeEffective"] = $vol->VOL_dateArriveeEffective;	

		return $volTab;
	}
	
	public function getVol($idVol){
		$vol = $this->find($idVol)->current();
		$volTab = $this->remplirVolTab($vol);
		
		return $volTab;
	}
	
	public function ajoutVol($idLigne, $dateDepart, $idAeroportDepart, $idAeroportArrivee, $idAvion, $idPilote, $idCopilote){
		$auth = Zend_Auth::getInstance();
		$identity = $auth->getIdentity();
		
		$tableVol = new Application_Model_DbTable_Vol();
		$vol = $tableVol->createRow();
		$vol->UTI_id_servicePlanning = $identity->UTI_id;
		$vol->AER_id_depart	= $idAeroportDepart;
		$vol->AER_id_arrivee = $idAeroportArrivee;
		$vol->LIG_id = $idLigne;
		$vol->AVI_id = $idAvion;
		$vol->PIL_id = $idPilote;
		$vol->PIL_id_copilote = $idCopilote;
		$vol->VOL_dateDepartPrevue = $dateDepart;
		$vol->VOL_dateAjout = date("Y-m-d H:i:s");
		$vol->VOL_dateSupression = null;
		$idVol = $vol->save();
		
		$avions = new Application_Model_DbTable_Avion();
		$avion = $avions->find($idAvion)->current();
		$typeAvion = $avion->findParentApplication_Model_DbTable_TypesAvion();
		$places = new Application_Model_DbTable_Places();
		for ($i = 0; $i < $typeAvion->TAVI_nombrePlaces; $i++) {
			$place = $places->createRow();
			$place->VOL_id = $idVol;
			$place->save();
		}
		
	}

	public function modifierVol($idVol, $dateDepart, $idAeroportDepart, $dateArrivee, $idAeroportArrivee, $idAvion, $idPilote, $idCopilote){
		$auth = Zend_Auth::getInstance();
		$identity = $auth->getIdentity();
	
		$vol= $this->find($idVol)->current();
		if ($identity->TUTI_alias == 'planning') {
			$vol->UTI_id_servicePlanning = $identity->UTI_id;
		}
		//$vol->UTI_id_servicePlanning = $identity->UTI_id;
		$vol->AER_id_depart	= $idAeroportDepart;
		$vol->AER_id_arrivee = $idAeroportArrivee;
		$vol->AVI_id = $idAvion;
		$vol->PIL_id = $idPilote;
		$vol->PIL_id_copilote = $idCopilote;
		$vol->VOL_dateDepartEffective = $dateDepart;
		$vol->VOL_dateArriveeEffective = $dateArrivee;
		
		$vol->save();
	}
	
	public function getVolsEnCours() {
		$sql = 'SELECT VOL_id, AER_id_depart, AER_id_arrivee, VOL_dateDepartEffective, VOL_dateArriveeEffective 
		  		FROM vols 
		  		WHERE VOL_dateDepartEffective IS NOT NULL';
		
		$aeroport = new Application_Model_DbTable_Aeroport();
	
		$tabRes = array();
		$res = $this->getDbAdapter()->fetchAll($sql);
		$i = 0;
		foreach ($res as $r) {
			$tabRes[$i]['VOL_id'] = $r['VOL_id'];
			$tabRes[$i]['AER_depart'] = ($aeroport->getNomAeroportById($r['AER_id_depart']));
			$tabRes[$i]['AER_arrivee'] = ($aeroport->getNomAeroportById($r['AER_id_arrivee']));
			$tabRes[$i]['VOL_dateDepartEffective'] = $r['VOL_dateDepartEffective'];
			$tabRes[$i]['VOL_dateArriveeEffective'] = $r['VOL_dateArriveeEffective'];	
			$i++;		
		}
		return $tabRes;
	}
	
	public function getVolsToday() {
		$sql = 'SELECT *, COUNT(incidents.VOL_id) AS nbIncidents 
				FROM vols, incidents
				WHERE vols.VOL_id = incidents.VOL_id
				GROUP BY incidents.VOL_id;';
		
		
		return $this->getDbAdapter()->fetchAll($sql);
	}
	
	public function getDbAdapter() {
		return Zend_Registry::get('db');
	}
}
