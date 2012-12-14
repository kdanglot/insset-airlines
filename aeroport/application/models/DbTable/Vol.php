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
					$calendrierVol[$i]["vol"][$tableauNbVolParJour[$i]]["ligne"]["LIG_id"] = $ligne->LIG_id;
					
					foreach ($vols as $vol){
						$dateDepart = DateTime::createFromFormat('Y-m-d H:i:s', $vol->VOL_dateDepartEffective);
						if($dateJour->format('Y-m-d') == $dateDepart->format('Y-m-d')){
							$calendrierVol[$i]["vol"][$tableauNbVolParJour[$i]] = $this->remplirJourneeCalendrier($vol);
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
						$calendrierVol[($i*7)+$j]["vol"][$tableauNbVolParJour[($i*7)+$j]]["ligne"]["LIG_id"] = $ligne->LIG_id;
						$tableauNbVolParJour[($i*7)+$j]++;
					}
					
					foreach ($vols as $vol){
						$dateDepart = DateTime::createFromFormat('Y-m-d H:i:s', $vol->VOL_dateDepartEffective);
						
						if($dateJour->format('W') == $dateDepart->format('W') && $dateJour->format('Y') == $dateDepart->format('Y')){
							$jour = $i*7+($dateDepart->format('N')-1);
							for ($j = 0; $j < 7; $j++) {
								$calendrierVol[($i*7)+$j]["vol"][$tableauNbVolParJour[($i*7)+$j]] = null;
								$tableauNbVolParJour[($i*7)+$j]--;
							}
							$calendrierVol[$jour]["vol"][$tableauNbVolParJour[$jour]]["ligne"]["LIG_id"] = $ligne->LIG_id;
							$tableauNbVolParJour[$jour]++;
							$calendrierVol[$jour]["vol"][$tableauNbVolParJour[$jour]] = $this->remplirJourneeCalendrier($vol);
							$tableauNbVolParJour[$jour]++;
						}
					}
					$dateJour = $dateJour->add(new DateInterval('P7D'));
				}
			}
		}
		
		return $calendrierVol;
	}
	
	public function remplirJourneeCalendrier($vol){
		$journe["VOL_id"] = $vol->VOL_id;
		
		$aeroport = new Application_Model_DbTable_Aeroport();
		$journe["aeroportDepart"]["AER_id_depart"] = $vol->AER_id_depart;
		$journe["aeroportDepart"]["AER_nom"] = $aeroport->find($vol->AER_id_depart)->current()->AER_nom;
		$journe["aeroportArrivee"]["AER_id_arrivee"] = $vol->AER_id_arrivee;
		$journe["aeroportArrivee"]["AER_nom"] = $aeroport->find($vol->AER_id_arrivee)->current()->AER_nom;
		
		$avion = $vol->findParentApplication_Model_DbTable_Avion();
		$journe["avion"]["AVI_id"] = $avion->AVI_id;
		$journe["avion"]["AVI_immatriculation"] = $avion->AVI_immatriculation;
		
		$pilotes = new Application_Model_DbTable_Pilote();
		$journe["pilote"]["PIL_id"] = $vol->PIL_id;
		$pilote = $pilotes->find($vol->PIL_id)->current();
			$utilisateur = $pilote->findParentApplication_Model_DbTable_Utilisateur();
			$journe["pilote"]["utilisateur"]["UTI_id"] = $utilisateur->UTI_id;
			$journe["pilote"]["utilisateur"]["UTI_nom"] = $utilisateur->UTI_nom;
			$journe["pilote"]["utilisateur"]["UTI_prenom"] = $utilisateur->UTI_prenom;
			$journe["pilote"]["utilisateur"]["UTI_mail"] = $utilisateur->UTI_mail;  
		$journe["coPilote"]["PIL_id"] = $vol->PIL_id_copilote;
		$pilote = $pilotes->find($vol->PIL_id_copilote)->current();
			$utilisateur = $pilote->findParentApplication_Model_DbTable_Utilisateur();
			$journe["coPilote"]["utilisateur"]["idUtilisateur"] = $utilisateur->UTI_id;
			$journe["copilote"]["utilisateur"]["UTI_nom"] = $utilisateur->UTI_nom;
			$journe["coPilote"]["utilisateur"]["UTI_prenom"] = $utilisateur->UTI_prenom;
			$journe["coPilote"]["utilisateur"]["UTI_mail"] = $utilisateur->UTI_mail;  
			
		$journe["VOL_dateDepartEffective"] = $vol->VOL_dateDepartEffective;
		$journe["VOL_dateArriveeEffective"] = $vol->VOL_dateArriveeEffective;	

		return $journe;
	}
	
// 	getVol

// 	creer($ligne, $dateDepart, $aeroportDepart, $dateArrivee, $aeroportArrivee, $avion, $pilote, $copilote);

// 	modifier($idVol, $ligne, $dateDepart, $aeroportDepart, $dateArrivee, $aeroportArrivee, $avion, $pilote, $copilote);

// getVols() : Parametre par defaut vide
}