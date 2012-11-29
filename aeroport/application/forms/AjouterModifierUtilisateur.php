<?php
class Application_Form_AjouterModifierUtilisateur extends Zend_Form {
		
	public function init() {
		$this->setMethod('POST');
		$this->setAttrib('id', 'ajouterModifierUtilisateur');

		$ePrenom = new Zend_Form_Element_Text('prenom');
		$ePrenom->setAttrib('id', 'prenom');
		
		$eNom = new Zend_Form_Element_Text('nom');
		$eNom->setAttrib('id', 'nom');
		
		$eLogin = new Zend_Form_Element_Text('login');
		$eLogin->setAttrib('id', 'login,');
		
		$eTypeUtilisateur = new Zend_Form_Element_Select('typeUtilisateur');
		$eTypeUtilisateur->setAttrib('id', 'typeUtilisateur');
		
		$eDateEmbauche = new Zend_Form_Element_Text('dateEmbauche');
		$eDateEmbauche->setAttrib('id', 'dateEmbauche');

		$this->addElements(array($ePrenom, $eNom, $eLogin, $eTypeUtilisateur, $eDateEmbauche));
	}
	
}