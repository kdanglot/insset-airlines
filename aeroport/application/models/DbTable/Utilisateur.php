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


	// public function getAliasTypeUtilisateur($idUtilisateur) {
		// $utilisateur = $this->find($idUtilisateur)->current();
		// $infos = $utilisateur->findParentApplication_Model_DbTable_TypeUtilisateur();
		// return $infos->TUTI_alias;
		
// 		$db = Zend_Registry::get('db');
// 		$sql = 'SELECT u.TUTI_id, TUTI_libelle 
// 				FROM utilisateurs u, typesutilisateurs tu
// 				WHERE u.TUTI_id = tu.TUTI_id
// 				AND u.TUTI_id = '.$idUtilisateur.';';
// 		$res = $db->fetchAll($sql);
// 		return $res;
	// }
	
	public function ajouterUtilisateur($idUtilisateur, $utilisateur) {
		$utilisateurBDD = $this->find($idUtilisateur)->current();
		
		$utilisateurBDD->UTI_nom = $utilisateur["UTI_nom"];
		$utilisateurBDD->UTI_prenom = $utilisateur["UTI_prenom"];
		$utilisateurBDD->UTI_login = $utilisateur["UTI_login"];
		$utilisateurBDD->UTI_password = $utilisateur["UTI_password"];
		$utilisateurBDD->UTI_dateEmbauche = $utilisateur["UTI_dateEmbauche"];
		
		$utilisateurBDD->save();
	}
	
	public function modifierUtilisateur($idUtilisateur, $utilisateur) {
		$utilisateurBDD = $this->find($idUtilisateur)->current();
		
		$utilisateurBDD->UTI_nom = $utilisateur["UTI_nom"];
		$utilisateurBDD->UTI_prenom = $utilisateur["UTI_prenom"];
		$utilisateurBDD->UTI_login = $utilisateur["UTI_login"];
		$utilisateurBDD->UTI_password = $utilisateur["UTI_password"];
		$utilisateurBDD->UTI_dateEmbauche = $utilisateur["UTI_dateEmbauche"];
		
		$utilisateurBDD->save();
	}
	
	public function supprimerUtilisateur($idUtilisateur) {
		$this->find($idUtilisateur)->current()->delete();
	}
} // Applicaion_Model_DbTable_User