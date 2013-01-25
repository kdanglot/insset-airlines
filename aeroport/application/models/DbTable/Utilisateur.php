<?php

class Application_Model_DbTable_Utilisateur extends Zend_Db_Table_Abstract {
	
	// nom de la de table
	protected $_name = 'utilisateurs';
	protected $primary = 'UTI_id';
    protected $_dependentTables = 'Application_Model_DbTable_Pilote';
	
	protected $_referenceMap = array (
				'TypeUtilisateur' => array(
							'columns' => 'TUTI_id', 
							'refColumns' => 'TUTI_id', 	
							'refTableClass' => 'Application_Model_DbTable_TypeUtilisateur'
					)
			);
	
	public function getUtilisateurCourant(){
		$utilisateurListe = $this->fetchAll();
		$utilisateurTab = array();
		$i = 0;
		
		foreach ($utilisateurListe as $utilisateur) {
			if ($utilisateur->UTI_dateSupression == "") {
				$utilisateurTab[$i]["UTI_id"] = $utilisateur->UTI_id;
				$utilisateurTab[$i]["UTI_nom"] = $utilisateur->UTI_nom;
				$utilisateurTab[$i]["UTI_prenom"] = $utilisateur->UTI_prenom;
				$utilisateurTab[$i]["UTI_login"] = $utilisateur->UTI_login;
				$utilisateurTab[$i]["UTI_mail"] = $utilisateur->UTI_mail;
				$typeUtilisateur = $utilisateur->findParentApplication_Model_DbTable_TypeUtilisateur();
				$utilisateurTab[$i]["typeUtilisateur"]["TUTI_id"] = $typeUtilisateur->TUTI_id;
				$utilisateurTab[$i]["typeUtilisateur"]["TUTI_alias"] = $typeUtilisateur->TUTI_alias;
				$utilisateurTab[$i]["typeUtilisateur"]["TUTI_label"] = $typeUtilisateur->TUTI_label;
				$i++;
			}
		}
		
		return $utilisateurTab;
	}
	
	public function ajouterUtilisateur($idUtilisateur, $utilisateur) {
		$utilisateurBDD = $this->find($idUtilisateur)->current();
		
		$utilisateurBDD->UTI_nom = $utilisateur["UTI_nom"];
		$utilisateurBDD->UTI_prenom = $utilisateur["UTI_prenom"];
		$utilisateurBDD->UTI_login = $utilisateur["UTI_login"];
		$utilisateurBDD->UTI_password = $utilisateur["UTI_password"];
		$utilisateurBDD->UTI_dateEmbauche = $utilisateur["UTI_dateEmbauche"];
		
		$utilisateurBDD->save();
	}
	
	public function modifierUtilisateur($idUtilisateur, array $utilisateur) {
		$utilisateurBDD = $this->find($idUtilisateur)->current();
		
		$utilisateurBDD->UTI_nom = $utilisateur["UTI_nom"];
		$utilisateurBDD->UTI_prenom = $utilisateur["UTI_prenom"];
		$utilisateurBDD->UTI_login = $utilisateur["UTI_login"];
		$utilisateurBDD->UTI_password = $utilisateur["UTI_password"];
		$utilisateurBDD->UTI_dateEmbauche = $utilisateur["UTI_dateEmbauche"];
		
		$utilisateurBDD->save();
	}
	
	public function supprimerUtilisateur($idUtilisateur) {
		return $this->delete('UTI_id =' . (int)$idUtilisateur);
	}
} // Applicaion_Model_DbTable_User