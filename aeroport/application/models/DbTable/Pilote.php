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
			$piloteTab[$i]["id"] = $pilote->PIL_id;
			$utilisateur = $pilote->findParentApplication_Model_DbTable_Utilisateur();
			$piloteTab[$i]["nom"] = $utilisateur->UTI_prenom . ' ' . $utilisateur->UTI_nom;
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
		$tableUtilisateur->supprimerUtilisateur($id);
// 		$tableUtilisateur->delete('UTI_id =' . $this->find($id)->current()->UTI_id);
	} 
}