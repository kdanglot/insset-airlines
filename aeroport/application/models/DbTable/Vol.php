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
		$tabVol = array();
		$i=0;
		
		foreach ($lignes as $ligne){
			$date = new DateTime(date('Y-m-d'));
			
			$tabVol[$i]["LIG_id"] = $ligne->LIG_id;
			$tabVol[$i]["LIG_heureDepart"] = $ligne->LIG_heureDepart;
			$tabVol[$i]["LIG_heureArrivee"] = $ligne->LIG_heureArrivee;
			
			$vols = $ligne->findApplication_Model_DbTable_Vol();
			$j=0;
			foreach ($vols as $vol){
				$tabVol[$i]["vols"][$j]["VOL_id"] =	$vol->VOL_id;
				$aeroport = new Application_Model_DbTable_Aeroport();
				$tabVol[$i]["vols"][$j]["aeroportDepart"]["AER_id_depart"] = $vol->AER_id_depart;
				$tabVol[$i]["vols"][$j]["aeroportDepart"]["AER_nom"] = $aeroport->find($vol->AER_id_depart)->current()->AER_nom;
				$tabVol[$i]["vols"][$j]["aeroportArrivee"]["AER_id_arrivee"] = $vol->AER_id_arrivee;
				$tabVol[$i]["vols"][$j]["aeroportArrivee"]["AER_nom"] = $aeroport->find($vol->AER_id_arrivee)->current()->AER_nom;
				$avion = $vol->findParentApplication_Model_DbTable_Avion();
				$tabVol[$i]["vols"][$j]["avion"]["AVI_id"] = $avion->AVI_id;
				$tabVol[$i]["vols"][$j]["avion"]["AVI_immatriculation"] = $avion->AVI_immatriculation;
				$pilotes = new Application_Model_DbTable_Pilote();
				$tabVol[$i]["vols"][$j]["pilote"]["PIL_id"] = $vol->PIL_id;
				$pilote = $pilotes->find($vol->PIL_id)->current();
				$utilisateur = $pilote->findParentApplication_Model_DbTable_Utilisateur();
				$tabVol[$i]["vols"][$j]["pilote"]["utilisateur"]["idUtilisateur"] = $utilisateur->UTI_id;
				$tabVol[$i]["vols"][$j]["pilote"]["utilisateur"] = $utilisateur->UTI_id;
// 				$tabVol[$i]["vols"][$j]["aeroportArrivee"]["AER_id_arrivee"] = $vol->AER_id_arrivee;
// 				$tabVol[$i]["vols"][$j]["aeroportArrivee"]["AER_nom"] = $aeroport->find($vol->AER_id_arrivee)->current()->AER_nom;
// 				PIL_id	
// 				PIL_id_copilote
				$j++;
			}
			
			$i++;
		}
	}
	
// 	getVol

// 	creer($ligne, $dateDepart, $aeroportDepart, $dateArrivee, $aeroportArrivee, $avion, $pilote, $copilote);

// 	modifier($idVol, $ligne, $dateDepart, $aeroportDepart, $dateArrivee, $aeroportArrivee, $avion, $pilote, $copilote);

// getVols() : Parametre par defaut vide
}