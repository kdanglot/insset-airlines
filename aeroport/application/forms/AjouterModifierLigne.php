<?php

class Application_Form_AjouterModifierligne extends Zend_Form {

	public function init() {
		$db = Zend_Registry::get('db');
		$aeroport = new Application_Model_DbTable_Aeroport();
		$aeroports = $aeroport->afficherLesAeroports();
		$pays = new Application_Model_DbTable_Pays();
		$lesPays = $pays->afficherLesPays();
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
			 
		$this->setMethod('post');
		$this->setAttrib('id', 'ajouterLigneFormulaire');

		$this->setDecorators(
			array(
        	'FormElements',
        	array('HtmlTag', array('tag' => 'table','class'=>'center')),
        	'Form'));

		
		// element hidden id
		$eIdLigne = new Zend_Form_Element_Hidden('id');
        $eIdLigne->addFilter('Int');
		 
		// element Text heure de depart + attributs
		$eHeureDepart = new Zend_Form_Element_Text('heureDepart');
		$eHeureDepart->setAttrib('placeholder', 'heureDepart');
		$eHeureDepart->setAttrib('autofocus', 'autofocus');
		$eHeureDepart->setLabel('Heure de départ : ');
		$eHeureDepart->setRequired(true);
		$eHeureDepart->addFilter('StringTrim');
		$eHeureDepart->addValidator('NotEmpty');
		$eHeureDepart->setDecorators($Decorator);

		// element Text heure d'arrive + attributs
		$eHeureArrivee = new Zend_Form_Element_Text('heureArrivee');
		$eHeureArrivee->setAttrib('placeholder', "Heure d'arrivée");
		$eHeureArrivee->setLabel("Heure d'arrivée");
		$eHeureArrivee->setRequired(true);
		$eHeureArrivee->addFilter('StringTrim');
		$eHeureArrivee->addValidator('NotEmpty');
		$eHeureArrivee->setDecorators($Decorator);

		
		// element Select pays de depart + attributs
		$ePaysDepart = new Zend_Form_Element_Select('paysDepart');
		$ePaysDepart->setLabel("Pays de départ :");
		$ePaysDepart->setAttrib('id', 'pays-depart');
		$ePaysDepart->setAttrib('name', 'pays-depart');
		$ePaysDepart->setDecorators($Decorator);

		$ePaysDepart->setAttrib('onchange', 'remplirSelect(this)');	
		$ePaysDepart->addMultiOption('-1', 'Choisissez un pays');
		foreach($lesPays as $p) {
			$ePaysDepart->addMultiOption($p['PAY_id'], $p['PAY_nom']);
		}		
		
		// element Text aeroport de depart + attributs
		$eAeroportDepart = new Zend_Form_Element_Select('aeroportDepart');
		$eAeroportDepart->setLabel("Aéroport de départ :");
		$eAeroportDepart->setAttrib('id', 'aeroport-depart');
		$eAeroportDepart->setDecorators($Decorator);
		$eAeroportDepart->setAttrib('name', 'aeroport-depart');
		$eAeroportDepart->addMultiOption('-1', 'Choisissez un aeroport');
	//	$eAeroportDepart->setRequired(true);	
		
		// element Select pays d'arrivé + attributs
		$ePaysArrive = new Zend_Form_Element_Select('paysArrive');
		$ePaysArrive->setLabel("Pays d'arrivé :");
		$ePaysArrive->setAttrib('name', 'pays-arrive');
		$ePaysArrive->setAttrib('id', 'pays-arrive');
		$ePaysArrive->setDecorators($Decorator);
		$ePaysArrive->setAttrib('onchange', 'remplirSelect(this)');
		$ePaysArrive->addMultiOption('-1', 'Choisissez un pays');
		foreach($lesPays as $p) {
		 $ePaysArrive->addMultiOption($p['PAY_id'], $p['PAY_nom']);
		}
		//$ePaysArrive->setRequired(true);
		
		// element Text aeroport d'arrive + attributs
		$eAeroportArrive = new Zend_Form_Element_Select('aeroportArrive');
		$eAeroportArrive->setLabel("Aéroport d'arrivé :");
		$eAeroportArrive->setDecorators($Decorator);
		$eAeroportArrive->setAttrib('name', 'aeroport-arrive');
		$eAeroportArrive->setAttrib('id', 'aeroport-arrive');
		$eAeroportArrive->addMultiOption('-1', 'Choisissez un aeroport');
	//	$eAeroportArrive->setRequired(true);
		
		// element Select periodicite + attributs
		$ePeriodicite = new Zend_Form_Element_Select('periodicite');
		$ePeriodicite->setLabel('Periodicite : ');
		$ePeriodicite->setDecorators($Decorator);
		$periodicites = new Application_Model_DbTable_TypePeriodicite();
		// var_dump($periodicites->fetchAll()); exit;
		$tabPeriodicites = array();
		foreach($periodicites->fetchAll() as $periodicite){
			$tabPeriodicites[$periodicite->TPER_id] = $periodicite->TPER_label;
		}
		$ePeriodicite->options = $tabPeriodicites;
		$ePeriodicite->setRequired(true);		

		// element Submit connexion + attributs
		$eAjouter = new Zend_Form_Element_Submit('ajouter');
		$eAjouter->setAttrib('id', 'boutonAjouter');
		$eAjouter->setDecorators($Decorator);


		// ajout des elements au formulaire
		$this->addElements(array($ePeriodicite, $eHeureDepart, $eHeureArrivee, $ePaysDepart, $eAeroportDepart, $ePaysArrive, $eAeroportArrive, $eAjouter));

	} // init()

} // Application_Form_Connexion
