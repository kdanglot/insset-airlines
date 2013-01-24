<?php
class Application_Form_ModifierPilote extends Zend_Form {

	public function init() {
		$typeBrevet = new Application_Model_DbTable_TypesBrevet();
		$typesBrevets = $typeBrevet->afficherLesBrevets();
		$Decorator = array(
    				'ViewHelper',
    				'Errors',
    				array('Description', array('tag' => 'p', 'class' => 'description')),
    				array('HtmlTag', array('tag' => 'td')),
    				array(array('tr' => 'HtmlTag'), array('tag' => 'tr')));

		$DecoratorSubmit = array(
            'ViewHelper',
            array(array('td' => 'HtmlTag'), array('tag' => 'td', 'colspan' => 2)),
            array(array('tr' => 'HtmlTag'), array('tag' => 'tr')));
	
		
	//	var_dump($typesBrevets);
		$this->setMethod('POST');
		$this->setAttrib('id', 'modifierPilote');
		$this->setDecorators(
			array(
        	'FormElements',
        	array('HtmlTag', array('tag' => 'table')),
        	'Form'));
		
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
		$eLogin->setAttrib('id', 'login');
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
		
		$eBrevet = new Zend_Form_Element_Checkbox('brevets');
		$eBrevet->setLabel('Brevets : ');
		
		
		$eBrevet->setChecked(true);
		
		/*foreach ($typesBrevets as $tb) {
			$eBrevet->addElements($tb['TBRE_id'], $tb['TBRE_nom']);
		}*/
		
		/*$eAncienMdp = new Zend_Form_Element_Password('ancienMdp');
		$eAncienMdp->setAttrib('id', 'ancienMdp');
		$eAncienMdp->setLabel('Ancien mdp :');
		
		$eNouveauMdp = new Zend_Form_Element_Password('nouveauMdp');
		$eNouveauMdp->setAttrib('id', 'mdp');
		$eNouveauMdp->setLabel('Nouveay mdp :');
		
		$eConfirmeMdp = new Zend_Form_Element_Password('ConfirmeMdp');
		$eConfirmeMdp->setAttrib('id', 'mdp');
		$eConfirmeMdp->setLabel('Confirme mdp :');*/
		
		$eModifier = new Zend_Form_Element_Submit('modifier');
		$eModifier->setAttrib('id', 'boutonModifier');
		
		$this->addElements(array($eId, $eNom, $ePrenom, $eLogin, $eMail, $eDateEmbauche, $eBrevet, $eModifier));
	}
}