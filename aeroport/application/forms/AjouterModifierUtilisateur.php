<?php
class Application_Form_AjouterModifierUtilisateur extends Zend_Form {
		
	public function init() {
		$this->setMethod('POST');
		$view = Zend_Layout::getMvcInstance()->getView();
		$this->setAction($view->baseUrl("administrateur/creerutilisateur"));
		$this->setAttrib('id', 'ajouterModifierUtilisateur');

		$ePrenom = new Zend_Form_Element_Text('prenom');
		$ePrenom->setAttrib('id', 'prenom');
		$ePrenom->setAttrib('placeholder', "Prenom");
		$ePrenom->setLabel("Prénom");
		$ePrenom->setRequired(true);
		$ePrenom->addFilter('StringTrim');
		$ePrenom->addValidator('NotEmpty');
		
		$eNom = new Zend_Form_Element_Text('nom');
		$eNom->setAttrib('id', 'nom');
		$eNom->setAttrib('placeholder', "Nom");
		$eNom->setLabel("Nom");
		$eNom->setRequired(true);
		$eNom->addFilter('StringTrim');
		$eNom->addValidator('NotEmpty');
		
		$eLogin = new Zend_Form_Element_Text('login');
		$eLogin->setAttrib('id', 'login');
		$eLogin->setAttrib('placeholder', "Login");
		$eLogin->setLabel("Login");
		$eLogin->setRequired(true);
		$eLogin->addFilter('StringTrim');
		$eLogin->addValidator('NotEmpty');
		
		$ePassword = new Zend_Form_Element_Password('password');
		$ePassword->setAttrib('id', 'pasword');
		$ePassword->setAttrib('placeholder', "Mot de passe");
		$ePassword->setLabel("Mot de passe");
		$ePassword->setRequired(true);
		$ePassword->addFilter('StringTrim');
		$ePassword->addValidator('NotEmpty');
		
		$eMail = new Zend_Form_Element_Text('mail');
		$eMail->setAttrib('id', 'mail');
		$eMail->setAttrib('placeholder', "Mail");
		$eMail->setLabel("Mail");
		$eMail->setRequired(true);
		$eMail->addFilter('StringTrim');
		$eMail->addValidator('NotEmpty');
		$eMail->addValidator(new Zend_Validate_EmailAddress());
		
		//Recuperation des type des utilisateurs
		$typesUtilisateurTable = new Application_Model_DbTable_TypeUtilisateur();
		$typesUtilisateurs = $typesUtilisateurTable->getTypesUtilisateurs();
		
		$eTypeUtilisateur = new Zend_Form_Element_Select('typeUtilisateur');
		$eTypeUtilisateur->setAttrib('id', 'typeUtilisateur');
		$eTypeUtilisateur->setLabel("Type utilisateur");
		$eTypeUtilisateur->setAttrib('name', 'typeUtilisateur');
		$tabUtis = array();
		foreach($typesUtilisateurs as $typeUtilisateur) {
			$tabUtis[$typeUtilisateur['TUTI_id']] = utf8_encode($typeUtilisateur['TUTI_label']);
		}
		$eTypeUtilisateur->options = $tabUtis;
		
		$eDateEmbauche = new Zend_Form_Element_Text('dateEmbauche');
		$eDateEmbauche->setAttrib('id', 'dateEmbauche');
		$eDateEmbauche->setAttrib('placeholder', "Date d'embauche");
		$eDateEmbauche->setLabel("Date d'embauche");
		$eDateEmbauche->setRequired(true);
		$eDateEmbauche->addFilter('StringTrim');
		$eDateEmbauche->addValidator('NotEmpty');
		$eDateEmbauche->addValidator( new Zend_Validate_Date(array('format' => 'yyyy-mm-dd')));

// 		element Submit connexion + attributs
		$eAjouter = new Zend_Form_Element_Submit('Ajouter');
		$eAjouter->setAttrib('id', 'boutonAjouter');

		$this->addElements(array($ePrenom, $eNom, $eLogin, $ePassword, $eMail, $eTypeUtilisateur, $eDateEmbauche, $eAjouter));
	}
	
}