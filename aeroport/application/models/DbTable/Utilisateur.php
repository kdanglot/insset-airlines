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