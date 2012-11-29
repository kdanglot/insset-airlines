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

}