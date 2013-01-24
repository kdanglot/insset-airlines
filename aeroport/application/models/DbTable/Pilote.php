<?php
class Application_Model_DbTable_Pilote extends Zend_Db_Table_Abstract {

	protected $_name = 'pilotes';
	protected $primary = 'PIL_id';
    protected $_dependentTables = 'Application_Model_DbTable_Brevets';
	
	protected $_referenceMap = array (
				'Utilisateur' => array(
							'columns' => 'UTI_id', 
							'refColumns' => 'UTI_id', 	
							'refTableClass' => 'Application_Model_DbTable_Utilisateur'
						)
			);
	
	
	public function getPiloteDisponible($dateDepartVoulue, $idAeroportDepartVoulue, $idAeroportArriveeVoulue){
		$piloteListe = $this->fetchAll();
		$piloteTab = array ();
		$i = 0;
	
// 		Recupperation des aéroports.
		$aeroport = new Application_Model_DbTable_Aeroport();
		$aeroportDepartVoulue = $aeroport->find($idAeroportDepartVoulue)->current();
		$aeroportArriveeVoulue = $aeroport->find($idAeroportArriveeVoulue)->current();
	
// 		Calcul de la distance entre les aeroports;
		$distanceAeroportVoulue = $this->calculDistance($aeroportDepartVoulue->AER_longitude, $aeroportDepartVoulue->AER_latitude, $aeroportArriveeVoulue->AER_longitude, $aeroportArriveeVoulue->AER_latitude);
	
		foreach ($piloteListe as $pilote){			
			$vols = new Application_Model_DbTable_Vol();	
			$volListe = $vols->fetchAll($vols->select()->from(array("vol" => "vols"))->where("vol.PIL_id = ".$pilote->PIL_id)->orWhere("vol.PIL_id_copilote = ".$pilote->PIL_id));
			$disponible = true;
			foreach ($volListe as $vol){
// 				On ne fouille les vols déja effectués.
				if ($vol->VOL_dateDepartEffective == "") {
// 					Recuperation de l'avion utilisé
					$avion = $vol->findParentApplication_Model_DbTable_Avion();
					
// 					Recuperation du type de l'avion utilisé
					$typeAvion = $avion->findParentApplication_Model_DbTable_TypesAvion();
					
// 					Creation d'un DateTime basée sur l'heure de depart prévue de l'avion.
					$dateDepartPrevue =  new DateTime($vol->VOL_dateDepartPrevue);
						
// 					Recupperation des aéroports.
					$aeroportDepart = $aeroport->find($vol->AER_id_depart)->current();
					$aeroportArrive = $aeroport->find($vol->AER_id_arrivee)->current();
						
// 					Calcul de la distance entre les aeroports;
					$distanceAeroport = $this->calculDistance($aeroportDepart->AER_longitude, $aeroportDepart->AER_latitude, $aeroportArrive->AER_longitude, $aeroportArrive->AER_latitude);
						
// 					Calcul du temps de trajets entre les aéroports
					$tempsTrajetEnSeconde = $this->calculDuree($distanceAeroport, $typeAvion->TAVI_vitessemoyenne);
						
// 					Calucul de la date d'arrivé de l'avion.
					$dateArriveePrevue = $dateDepartPrevue->add(new DateInterval('PT'.round($tempsTrajetEnSeconde, 0, PHP_ROUND_HALF_DOWN).'S'));
						
// 					Calcul du temps de trajets entre les aéroports
					$tempsTrajetEnSecondeVoulue = $this->calculDuree($distanceAeroportVoulue, $typeAvion->TAVI_vitessemoyenne);
						
// 					Calucul de la date d'arrivé de l'avion.
					$dateDepartVoulueClone = clone $dateDepartVoulue;
					$dateArriveeVoulue = $dateDepartVoulueClone->add(new DateInterval('PT'.round($tempsTrajetEnSecondeVoulue, 0, PHP_ROUND_HALF_DOWN).'S'));
						
					if($dateDepartPrevue < $dateArriveeVoulue  && $dateArriveePrevue > $dateDepartVoulue){
						$disponible = false;
					}
				}
			}
			if ($disponible) {
				$piloteTab[$i]["PIL_id"] = $pilote->PIL_id;
				$utilisateur = $pilote->findParentApplication_Model_DbTable_Utilisateur();
				$piloteTab[$i]["UTI_id"] = $utilisateur->UTI_id;
				$piloteTab[$i]["UTI_nom"] = $utilisateur->UTI_nom;
				$piloteTab[$i]["UTI_prenom"] = $utilisateur->UTI_prenom;
				$piloteTab[$i]["UTI_login"] = $utilisateur->UTI_login;
				$piloteTab[$i]["UTI_mail"] = $utilisateur->UTI_mail;
				$i++;
			}
		}
		
		var_dump($piloteTab);
// 		return $piloteTab;
	}
	
	public function calculDuree($distance, $vitesseMoyenne){
		return $distance/($vitesseMoyenne/(60*60));
		
	}
	
	public function calculDistance($lon1, $lat1, $lon2, $lat2){
		$R = 6371; // km
		$dLat = deg2rad(($lat2-$lat1));
		$dLon = deg2rad(($lon2-$lon1));
		$lat1 = deg2rad($lat1);
		$lat2 = deg2rad($lat2);
		
		$a = sin($dLat/2) * sin($dLat/2) + sin($dLon/2) * sin($dLon/2) * cos($lat1) * cos($lat2);
		$c = 2 * atan2((sqrt($a)), sqrt(1-$a));
		$d = $R * $c;
		return $d;
	}
	
	public function afficherPilote($idPilote) {
		$pilote = $this->find($idPilote)->current();
		$utilisateur = $pilote->findParentApplication_Model_DbTable_Utilisateur();
		$listeBrevets = $pilote->findApplication_Model_DbTable_TypesBrevetViaApplication_Model_DbTable_Brevets();
		$tabPilote = array();
		$tabPilote[0]['utilisateur']['UTI_nom'] = $utilisateur->UTI_nom;
		$tabPilote[0]['utilisateur']['UTI_prenom'] = $utilisateur->UTI_prenom;
		$tabPilote[0]['utilisateur']['UTI_login'] = $utilisateur->UTI_login;
		$tabPilote[0]['utilisateur']['UTI_mail'] = $utilisateur->UTI_mail;
		$tabPilote[0]['utilisateur']['UTI_dateEmbauche'] = $utilisateur->UTI_dateEmbauche;
		$j = 0;
		foreach ($listeBrevets as $brevet) {
			$tabPilote[0]['brevets'][$j]['TRBE_id'] = $brevet->TBRE_id;
			$tabPilote[0]['brevets'][$j]['TRBE_nom'] = $brevet->TBRE_nom;
			$tabPilote[0]["brevets"][$j]["BRE_dateAjout"] = $brevet->TBRE_dateAjout;
			$j++;
		}
		return $tabPilote;
	}
	
	public function afficherLesPilotes() {
		$piloteListe = $this->fetchAll();
		$piloteTab = array ();
		$i = 0;
		
		foreach ($piloteListe as $pilote) {
			$piloteTab[$i]["PIL_id"] = $pilote->PIL_id;
			$utilisateur = $pilote->findParentApplication_Model_DbTable_Utilisateur();
			$piloteTab[$i]["utilisateur"]["UTI_id"] = $utilisateur->UTI_id;	
			$piloteTab[$i]["utilisateur"]["UTI_nom"] = $utilisateur->UTI_nom;
			$piloteTab[$i]["utilisateur"]["UTI_prenom"] = $utilisateur->UTI_prenom;
			$piloteTab[$i]["utilisateur"]["UTI_login"] = $utilisateur->UTI_login;
			$piloteTab[$i]["utilisateur"]["UTI_password"] = $utilisateur->UTI_password;	
			$piloteTab[$i]["utilisateur"]["UTI_dateEmbauche"] = $utilisateur->UTI_dateEmbauche;	
			$piloteTab[$i]["utilisateur"]["UTI_dateAjout"] = $utilisateur->UTI_dateAjout;
			$piloteTab[$i]["utilisateur"]["UTI_dateSupression"] = $utilisateur->UTI_dateSupression;
			$listeBrevets = $pilote->findApplication_Model_DbTable_TypesBrevetViaApplication_Model_DbTable_Brevets();
			$j = 0;
			foreach ($listeBrevets as $brevet) {
				$piloteTab[$i]["brevets"][$j]["TBRE_id"] = $brevet->TBRE_id;
				$piloteTab[$i]["brevets"][$j]["TBRE_nom"] = $brevet->TBRE_nom;
				$piloteTab[$i]["brevets"][$j]["BRE_dateAjout"] = $brevet->TBRE_dateAjout;
				$piloteTab[$i]["brevets"][$j]["TBRE_dateSupression"] = $brevet->TBRE_dateSupression;
				$j++;
			}
			$i++;
		}
		
		return $piloteTab;
	}
	
	public function afficherListePilotesDisponibles() {
		$piloteListe = $this->fetchAll();
		$piloteTab = array ();
		$i = 0;
		
		foreach ($piloteListe as $pilote) {
			$utilisateur = $pilote->findParentApplication_Model_DbTable_Utilisateur();
			$piloteTab[$pilote->PIL_id] = $utilisateur->UTI_prenom . ' ' . $utilisateur->UTI_nom;
			$i++;
		}
		
		return $piloteTab;
	}
	
	public function ajouterPilote($UTI_nom, $UTI_prenom, $UTI_login, $UTI_password, $UTI_mail, $UTI_dateEmbauche, $UTI_dateAjout, array $idBrevets) {
		$tableTypeUtilisateur = new Application_Model_DbTable_TypeUtilisateur();
		$tableUtilisateur = new Application_Model_DbTable_Utilisateur();
		$utilisateur = $tableUtilisateur->createRow();
		$utilisateur->TUTI_id = 5;
		$utilisateur->UTI_nom = $UTI_nom;
		$utilisateur->UTI_prenom	= $UTI_prenom;
		$utilisateur->UTI_login = $UTI_login;
		$utilisateur->UTI_password = $UTI_password;
		$utilisateur->UTI_mail = $UTI_mail;
		$utilisateur->UTI_dateEmbauche = $UTI_dateEmbauche;
		$utilisateur->UTI_dateAjout = $UTI_dateAjout;
		$utilisateur->UTI_dateSupression = null;
		$idUtilisateur = $utilisateur->save();
		$tablePilote = new Application_Model_DbTable_Pilote();
		$pilote = $tablePilote->createRow();
		$pilote->UTI_id = $idUtilisateur;
		$idPilote = $pilote->save();
		$tableBrevets = new Application_Model_DbTable_Brevets();
		for ($i = 0; $i < count($idBrevets); $i++) {
			$brevet = $tableBrevets->createRow();
			$brevet->PIL_id = $idPilote;
			$brevet->TBRE_id = $idBrevets[$i]["idBrevets"];
			$brevet->BRE_dateFin = $idBrevets[$i]["dateFin"];
			$brevet->save();
		}
	}
	
	public function modifierPilote($idPilote, array $pilote) {
// 		Tableau a envoyer
// 				$p["UTI_nom"] = "GUN";
// 				$p["UTI_prenom"] = "TOP";
// 				$p["UTI_login"] = "top";
// 				$p["UTI_password"] = "top";
// 				$p["UTI_dateEmbauche"] = "2012-11-28";
		$tableUtilisateur = new Application_Model_DbTable_Utilisateur();
		$tableUtilisateur->modifierUtilisateur($this->find($idPilote)->current()->UTI_id, $pilote);
	} 
	
	public function supprimerPilote($id) {
		$tableUtilisateur = new Application_Model_DbTable_Utilisateur();
		$res = $tableUtilisateur->supprimerUtilisateur($id);
		return $res;
// 		$tableUtilisateur->delete('UTI_id =' . $this->find($id)->current()->UTI_id);
	} 
}