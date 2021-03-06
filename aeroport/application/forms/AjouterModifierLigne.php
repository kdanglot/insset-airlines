<?php

class Application_Form_AjouterModifierligne extends Zend_Form {

	public function init() {
		$db = Zend_Registry::get('db');
		$aeroport = new Application_Model_DbTable_Aeroport();
		$aeroports = $aeroport->afficherLesAeroports();
		$pays = new Application_Model_DbTable_Pays();
		$lesPays = $pays->afficherLesPays();
			 
		$this->setMethod('post');
		$this->setAttrib('id', 'ajouterLigneFormulaire');

		
		// element hidden id
		$eIdLigne = new Zend_Form_Element_Hidden('id');
        $eIdLigne->addFilter('Int');
		 
		// element Text heure de depart + attributs
		$eHeureDepart = new Zend_Form_Element_Text('heureDepart');
		$eHeureDepart->setAttrib('placeholder', 'heure Départ');
		$eHeureDepart->setAttrib('autofocus', 'autofocus');
		$eHeureDepart->setLabel('Heure de départ : ');
		$eHeureDepart->setRequired(true);
		$eHeureDepart->addFilter('StringTrim');
		$eHeureDepart->setAttrib('required', 'required');
		$eHeureDepart->addValidator(new Zend_Validate_Date(array('format' => 'HH:ii')));

		// element Text heure d'arrive + attributs
		$eHeureArrivee = new Zend_Form_Element_Text('heureArrivee');
		$eHeureArrivee->setAttrib('placeholder', "heure arrivée");
		$eHeureArrivee->setLabel("Heure de arrivée : ");
		$eHeureArrivee->setRequired(true);
		$eHeureArrivee->addFilter('StringTrim');
		$eHeureDepart->setAttrib('required', 'required');
		$eHeureDepart->addValidator(new Zend_Validate_Date(array('format' => 'HH:ii')));

		
		// element Select pays de depart + attributs
		$ePaysDepart = new Zend_Form_Element_Select('paysDepart');
		$ePaysDepart->setLabel("Pays de départ :");
		$ePaysDepart->setAttrib('id', 'pays-depart');
		$ePaysDepart->setAttrib('name', 'pays-depart');

		$ePaysDepart->setAttrib('onchange', 'remplirSelect(this)');	
		$ePaysDepart->addMultiOption('-1', 'Choisissez un pays');
		foreach($lesPays as $p) {
			$ePaysDepart->addMultiOption($p['PAY_id'], $p['PAY_nom']);
		}		
		
		// element Text aeroport de depart + attributs
		$eAeroportDepart = new Zend_Form_Element_Select('aeroportDepart');
		$eAeroportDepart->setLabel("Aéroport de départ :");
		$eAeroportDepart->setAttrib('id', 'aeroport-depart');
		$eAeroportDepart->setAttrib('name', 'aeroport-depart');
		$eAeroportDepart->addMultiOption('-1', 'Choisissez un aeroport');
		$eAeroportDepart->setRequired(true);	
		
		// element Select pays d'arrivé + attributs
		$ePaysArrive = new Zend_Form_Element_Select('paysArrive');
		$ePaysArrive->setLabel("Pays d'arrivé :");
		$ePaysArrive->setAttrib('name', 'pays-arrive');
		$ePaysArrive->setAttrib('id', 'pays-arrive');
		$ePaysArrive->setAttrib('onchange', 'remplirSelect(this)');
		$ePaysArrive->addMultiOption('-1', 'Choisissez un pays');
		foreach($lesPays as $p) {
		 $ePaysArrive->addMultiOption($p['PAY_id'], $p['PAY_nom']);
		}
		$ePaysArrive->setRequired(true);
		
		// element Text aeroport d'arrive + attributs
		$eAeroportArrive = new Zend_Form_Element_Select('aeroportArrive');
		$eAeroportArrive->setLabel("Aéroport d'arrivé :");
		$eAeroportArrive->setAttrib('name', 'aeroport-arrive');
		$eAeroportArrive->setAttrib('id', 'aeroport-arrive');
		$eAeroportArrive->addMultiOption('-1', 'Choisissez un aeroport');
		$eAeroportArrive->setRequired(true);
		
		// element Select periodicite + attributs
		$ePeriodicite = new Zend_Form_Element_Select('periodicite');
		$ePeriodicite->setLabel('Periodicite : ');
		$periodicites = new Application_Model_DbTable_TypePeriodicite();
		// var_dump($periodicites->fetchAll()); exit;
		foreach($periodicites->fetchAll() as $periodicite){
			if($periodicite->TPER_id > 0){
				$ePeriodicite->addMultiOption($periodicite['TPER_id'], $periodicite['TPER_alias']);
			}
		}
		$ePeriodicite->setRequired(true);		

		// element Submit connexion + attributs
		$eAjouter = new Zend_Form_Element_Submit('ajouter');
		$eAjouter->setAttrib('id', 'boutonAjouter');


		// ajout des elements au formulaire
		$this->addElements(array($eIdLigne, $ePeriodicite, $eHeureDepart, $eHeureArrivee, $ePaysDepart, $eAeroportDepart, $ePaysArrive, $eAeroportArrive, $eAjouter));

	} // init()

} // Application_Form_Connexion
