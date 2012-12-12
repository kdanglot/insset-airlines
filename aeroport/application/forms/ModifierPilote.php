<?php
class Application_Form_ModifierPilote extends Zend_Form {

	public function init() {
		$this->setMethod('POST');
		$this->setAttrib('id', 'modifierPilote');
		
		$eId = new Zend_Form_Element_Hidden('id');
		$eId->setAttrib('id', 'id');
				
		$ePrenom = new Zend_Form_Element_Text('prenom');
		$ePrenom->setAttrib('id', 'prenom');
		$ePrenom->setLabel('Prénom :');
		$ePrenom->setRequired(true);
		$ePrenom->addValidator('NotEmpty');
		
		$eNom = new Zend_Form_Element_Text('nom');
		$eNom->setAttrib('id', 'nom');
		$eNom->setLabel('Nom :');
		$eNom->setRequired(true);
		$eNom->addValidator('NotEmpty');
		
		$eLogin = new Zend_Form_Element_Text('login');
		$eLogin->setAttrib('id', 'login,');
		$eLogin->setLabel('Login :');
		$eLogin->setRequired(true);
		$eLogin->addValidator('NotEmpty');
		
		$eAncienMdp = new Zend_Form_Element_Password('ancienMdp');
		$eAncienMdp->setAttrib('id', 'ancienMdp');
		$eAncienMdp->setLabel('Ancien mdp :');
		$eAncienMdp->setRequired(true);
		$eAncienMdp->addValidator('NotEmpty');
		
		$eNouveauMdp = new Zend_Form_Element_Password('nouveauMdp');
		$eNouveauMdp->setAttrib('id', 'mdp');
		$eNouveauMdp->setLabel('Nouveay mdp :');
		$eNouveauMdp->setRequired(true);
		$eNouveauMdp->addValidator('NotEmpty');
		
		$eConfirmeMdp = new Zend_Form_Element_Password('ConfirmeMdp');
		$eConfirmeMdp->setAttrib('id', 'mdp');
		$eConfirmeMdp->setLabel('Confirme mdp :');
		$eConfirmeMdp->setRequired(true);
		$eConfirmeMdp->addValidator('NotEmpty');
	}
}