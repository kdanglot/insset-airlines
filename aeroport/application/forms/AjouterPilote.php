<?php
class Application_Form_AjouterPilote extends Zend_Form {
		
	public function init() {
		$typeBrevet = new Application_Model_DbTable_TypesBrevet();
		$lesTypesBrevets = $typeBrevet->afficherLesBrevets();
		
		$loginDoesntExist = new Zend_Validate_Db_NoRecordExists('utilisateurs', 'UTI_login');
		$mailDoesntExist = new Zend_Validate_Db_NoRecordExists('utilisateurs', 'UTI_mail');
		$mailFormat = new Zend_Validate_EmailAddress();
	

		$this->setMethod('POST');
		$this->setAttrib('id', 'ajouterPilote');

		$ePrenom = new Zend_Form_Element_Text('prenom');
		$ePrenom->setAttrib('id', 'prenom');
		$ePrenom->setAttrib('placeholder', 'Prénom');
		$ePrenom->setRequired(true);
		$ePrenom->addFilter('Alnum');
		$ePrenom->addValidator('NotEmpty');
		

		$eNom = new Zend_Form_Element_Text('nom');
		$eNom->setAttrib('id', 'nom');
		$eNom->setAttrib('placeholder', 'Nom');
		$eNom->setRequired(true);
		$eNom->addFilter('Alnum');
		$eNom->addValidator('NotEmpty');

		
		$eLogin = new Zend_Form_Element_Text('login');
		$eLogin->setAttrib('id', 'login,');
		$eLogin->setAttrib('placeholder', 'Login');
		$eLogin->setRequired(true);
		$eLogin->addFilter('Alnum');
		$eLogin->addValidator('NotEmpty');
		$eLogin->addValidator($loginDoesntExist);

		
		$eMdp = new Zend_Form_Element_Password('mdp');
		$eMdp->setAttrib('id', 'mdp');
		$eMdp->setAttrib('placeholder', 'Mot de passe');
		$eMdp->setRequired(true);
		$eMdp->addValidator('NotEmpty');

		
		$eMdpConfirme = new Zend_Form_Element_Password('mdpConfirme');
		$eMdpConfirme->setAttrib('id', 'mdpConfirme');
		$eMdpConfirme->setAttrib('placeholder', 'Confirmez mot de passe');
		$eMdpConfirme->setRequired(true);
		$eMdpConfirme->addValidator('NotEmpty');
		$eMdpConfirme->addValidator((new Zend_Validate_Identical('mdp')));

		
		$eMail = new Zend_Form_Element_Text('email');
		$eMail->setAttrib('id', 'email');
		$eMail->setAttrib('placeholder', 'E-mail');
		$eMail->setRequired(true);
		$eMail->addValidator('NotEmpty');
		$eMail->addValidator($mailDoesntExist);
		$eMail->addValidator($mailFormat);

		
		$eTypeBrevet = new Zend_Form_Element_Multiselect('typeBrevet');
		$eTypeBrevet->setAttrib('id', 'typeBrevet');
		$eTypeBrevet->setAttrib('size', '5');
		$eTypeBrevet->setLabel('Type de brevet :');
		$eTypeBrevet->setRequired(true);

		$eTypeBrevet->addMultiOption('-1', 'Choisir un/des brevets');
		foreach($lesTypesBrevets as $b) {
			$eTypeBrevet->addMultiOption($b['TBRE_id'], $b['TBRE_nom']);
		}
		
		$eDateEmbauche = new Zend_Form_Element_Text('dateEmbauche');
		$eDateEmbauche->setAttrib('id', 'dateEmbauche');
		$eDateEmbauche->setAttrib('placeholder', 'Date embauche');
		$eDateEmbauche->setRequired(true);

		
		$eValider = new Zend_Form_Element_Submit('valider');
		$eValider->setAttrib('id', 'valider');


		$this->addElements(array($ePrenom, $eNom, $eLogin, $eMdp, $eMdpConfirme, $eMail, $eTypeBrevet, $eDateEmbauche,
			 $eValider));
	}
	
}