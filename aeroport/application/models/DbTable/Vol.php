<?php
class Application_Model_DbTable_Vol extends Zend_Db_Table_Abstract {
	protected $_name = 'vols';
	protected $primary = 'VOL_id';
	
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
	
	public function afficherVolPlanning(){
		$tabLignes = new Application_Model_DbTable_Ligne();
		$lignes = $tabLignes->fetchAll();
		$calendrierVol = array();
		
		for ($i = 0; $i < 35; $i++) {
			$tableauNbVolParJour[$i] = 0;
		}
		
		foreach ($lignes as $ligne){
			$periodicite = $ligne->findParentApplication_Model_DbTable_TypePeriodicite();
			$vols = $ligne->findApplication_Model_DbTable_Vol();
			if ($periodicite->TPER_label == "journalliers") {
				$date = new DateTime(date('Y-m-d'));
				$dateJour = $date->sub(new DateInterval('P'.($date->format('N')-1).'D'));
				for ($i = 0; $i < 35; $i++) {
					$calendrierVol[$i][$tableauNbVolParJour[$i]]["ligne"]["LIG_id"] = $ligne->LIG_id;
					
					foreach ($vols as $vol){
						$dateDepart = DateTime::createFromFormat('Y-m-d H:i:s', $vol->VOL_dateDepartEffective);
						if($dateJour->format('Y-m-d') == $dateDepart->format('Y-m-d')){
							$calendrierVol[$i][$tableauNbVolParJour[$i]] = $this->remplirVolTab($vol);
						}
					}
					
					$dateJour = $dateJour->add(new DateInterval('P1D'));
					$tableauNbVolParJour[$i]++;
				}
			}else if ($periodicite->TPER_label == "hebdomadaire") {
				$date = new DateTime(date('Y-m-d'));
				$dateJour = $date->sub(new DateInterval('P'.($date->format('N')-1).'D'));
				for ($i = 0; $i < 5; $i++) {
					for ($j = 0; $j < 7; $j++) {
						$calendrierVol[($i*7)+$j][$tableauNbVolParJour[($i*7)+$j]]["ligne"]["LIG_id"] = $ligne->LIG_id;
						$tableauNbVolParJour[($i*7)+$j]++;
					}
					
					foreach ($vols as $vol){
						$dateDepart = DateTime::createFromFormat('Y-m-d H:i:s', $vol->VOL_dateDepartEffective);
						
						if($dateJour->format('W') == $dateDepart->format('W') && $dateJour->format('Y') == $dateDepart->format('Y')){
							$jour = $i*7+($dateDepart->format('N')-1);
							for ($j = 0; $j < 7; $j++) {
								$calendrierVol[($i*7)+$j][$tableauNbVolParJour[($i*7)+$j]] = null;
								$tableauNbVolParJour[($i*7)+$j]--;
							}
							$calendrierVol[$jour][$tableauNbVolParJour[$jour]]["ligne"]["LIG_id"] = $ligne->LIG_id;
							$tableauNbVolParJour[$jour]++;
							$calendrierVol[$jour][$tableauNbVolParJour[$jour]] = $this->remplirVolTab($vol);
							$tableauNbVolParJour[$jour]++;
						}
					}
					$dateJour = $dateJour->add(new DateInterval('P7D'));
				}
			}
		}
		
		return $calendrierVol;
	}
	
	public function remplirVolTab($vol){
		$volTab["VOL_id"] = $vol->VOL_id;
		
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
			$volTab["copilote"]["utilisateur"]["UTI_nom"] = $utilisateur->UTI_nom;
			$volTab["coPilote"]["utilisateur"]["UTI_prenom"] = $utilisateur->UTI_prenom;
			$volTab["coPilote"]["utilisateur"]["UTI_mail"] = $utilisateur->UTI_mail;  
			
		$volTab["VOL_dateDepartEffective"] = $vol->VOL_dateDepartEffective;
		$volTab["VOL_dateArriveeEffective"] = $vol->VOL_dateArriveeEffective;	

		return $volTab;
	}
	
	
	
	public function getVol($idVol){
		$vol = $this->find($idVol)->current();
		$volTab = $this->remplirVolTab($vol);
		
		return $volTab;
	}
	
	public function ajoutVol($idLigne, $dateDepart, $idAeroportDepart, $dateArrivee, $idAeroportArrivee, $idAvion, $idPilote, $idCopilote){
		
	}

// 	creer($ligne, $dateDepart, $aeroportDepart, $dateArrivee, $aeroportArrivee, $avion, $pilote, $copilote);

// 	modifier($idVol, $ligne, $dateDepart, $aeroportDepart, $dateArrivee, $aeroportArrivee, $avion, $pilote, $copilote);
}