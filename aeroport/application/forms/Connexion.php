<?php

class Application_Form_Connexion extends Zend_Form {

    public function init() {
       
		$this->setMethod('post');
    	$this->setAttrib('id', 'formulaireConnexion');
    	
		// element Text login + attributs
		$eLogin = new Zend_Form_Element_Text('login');
		$eLogin->setAttrib('placeholder', 'identifiant');
		$eLogin->setAttrib('autofocus', 'autofocus');
		$eLogin->setLabel('Login : ');
		$eLogin->setRequired(true);
		$eLogin->addFilter('StringTrim');
		$eLogin->addValidator('NotEmpty');
		
		// element Password mdp + attributs
		$eMdp = new Zend_Form_Element_Password('mdp');
		$eMdp->setAttrib('placeholder', 'mot de passe');
		$eMdp->setLabel('Mot de passe : ');
		$eMdp->setRequired(true);
		$eMdp->addFilter('StringTrim');
		$eMdp->addValidator('NotEmpty');

		// element Submit connexion + attributs
		$eConnexion = new Zend_Form_Element_Submit('Connexion');
		$eConnexion->setAttrib('id', 'boutonConnexion');
		
		// element Checkbox checkbox + attributs
		// $eCheckbox = new Zend_Form_Element_Checkbox('checkbox');
		// $eCheckbox->setAttrib('id', 'checkboxConnexion');
		// $eCheckbox->setLabel('Garder ma session active');
		
		// ajout des elements au formulaire
		$this->addElements(array($eLogin, $eMdp, $eConnexion));
		
    } // init()    

} // Application_Form_Connexion

