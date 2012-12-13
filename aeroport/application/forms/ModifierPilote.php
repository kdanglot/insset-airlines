<?php
class Application_Form_ModifierPilote extends Zend_Form {

	public function init() {
		$this->setMethod('POST');
		$this->setAttrib('id', 'modifierPilote');
		
		$eId = new Zend_Form_Element_Text('id');
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
		
		$eMail = new Zend_Form_Element_Text('email');
		$eMail->setAttrib('id', 'email');
		$eMail->setLabel('Email');
		$eMail->setRequired(true);
		$eMail->addValidator('NotEmpty');
		
		$eDateEmbauche = new Zend_Form_Element_Text('dateEmbauche');
		$eDateEmbauche->setAttrib('id', 'dateEmbauche');
		$eDateEmbauche->setLabel('Date embauche');
		$eDateEmbauche->setRequired(true);
		
		$eAncienMdp = new Zend_Form_Element_Password('ancienMdp');
		$eAncienMdp->setAttrib('id', 'ancienMdp');
		$eAncienMdp->setLabel('Ancien mdp :');
		
		$eNouveauMdp = new Zend_Form_Element_Password('nouveauMdp');
		$eNouveauMdp->setAttrib('id', 'mdp');
		$eNouveauMdp->setLabel('Nouveay mdp :');
		
		$eConfirmeMdp = new Zend_Form_Element_Password('ConfirmeMdp');
		$eConfirmeMdp->setAttrib('id', 'mdp');
		$eConfirmeMdp->setLabel('Confirme mdp :');
		
		$eModifier = new Zend_Form_Element_Submit('modifier');
		$eModifier->setAttrib('id', 'boutonModifier');
		
		$this->addElements(array($eId, $eNom, $ePrenom, $eLogin, $eMail, $eDateEmbauche, $eAncienMdp, $eNouveauMdp, $eConfirmeMdp, $eModifier));
	}
}