<?php

class Application_Form_AjouterModifierligne extends Zend_Form {

	public function init() {
		$db = Zend_Registry::get('db');
		$aeroport = new Application_Model_DbTable_Aeroport();
		$aeroports = $aeroport->afficherLesAeroports();
		$pays = new Application_Model_DbTable_Pays();
		$lesPays = $pays->afficherLesPays();
		var_dump($lesPays);
		 
		$this->setMethod('post');
		$this->setAttrib('id', 'ajouterLigneFormulaire');
		
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

		// element Text heure d'arrive + attributs
		$eHeureArrivee = new Zend_Form_Element_Text('heureArrivee');
		$eHeureArrivee->setAttrib('placeholder', "Heure d'arrivée");
		$eHeureArrivee->setLabel("Heure d'arrivée");
		$eHeureArrivee->setRequired(true);
		$eHeureArrivee->addFilter('StringTrim');
		$eHeureArrivee->addValidator('NotEmpty');
		
		// element Select pays de depart + attributs
		$ePaysDepart = new Zend_Form_Element_Select('paysDepart');
		$ePaysDepart->setLabel("Pays de départ :");
		$ePaysDepart->setAttrib('id', 'p1');
		
		$ePaysDepart->setAttrib('onChange', 'go();');
		$ePaysDepart->addMultiOption('-1', 'Choisissez un pays');
		foreach($lesPays as $p) {
			$ePaysDepart->addMultiOption($p['PAY_id'], $p['PAY_nom']);
		}
		// $ePaysDepart->setRequired(true);
		
		// element Text aeroport de depart + attributs
		$eAeroportDepart = new Zend_Form_Element_Select('aeroportDepart');
		$eAeroportDepart->setLabel("Aéroport de départ :");
		$eAeroportDepart->setAttrib('id', 'a1');
		$eAeroportDepart->setAttrib('name', 'a1');
		$eAeroportDepart->addMultiOption('-1', 'Choisissez un aeroport');
		/*foreach($aeroports as $a) {
			$eAeroportDepart->addMultiOption($a['AER_id'], $a['AER_nom']);
		}*/
		// $eAeroportDepart->setRequired(true);	
		
		// element Select pays d'arrivé + attributs
		$ePaysArrive = new Zend_Form_Element_Select('paysArrive');
		$ePaysArrive->setLabel("Pays d'arrivé :");
		foreach($lesPays as $p) {
		 $ePaysArrive->addMultiOption($p['PAY_id'], $p['PAY_nom']);
		}
		// $ePaysArrive->setRequired(true);
		
		// element Text aeroport d'arrive + attributs
		$eAeroportArrive = new Zend_Form_Element_Select('aeroportArrive');
		$eAeroportArrive->setLabel("Aéroport d'arrivé :");
		foreach($aeroports as $a) {
			$eAeroportArrive->addMultiOption($a['AER_id'], $a['AER_nom']);
		}
		// $eAeroportArrive->setRequired(true);
		
		// element Select periodicite + attributs
		$ePeriodicite = new Zend_Form_Element_Select('periodicite');
		$ePeriodicite->setLabel('Periodicite : ');
		$ePeriodicite->addMultiOptions(array('unique'=>'unique','journalier'=>'journalier','hebdomadaire'=>'hebdomadaire','mensuel'=>'mensuel','annuel'=>'annuel'));
		

		// element Submit connexion + attributs
		$eAjouter = new Zend_Form_Element_Submit('ajouter');
		$eAjouter->setAttrib('id', 'boutonAjouter');

		// ajout des elements au formulaire
		$this->addElements(array($eHeureDepart, $eHeureArrivee, $ePeriodicite, $ePaysDepart, $eAeroportDepart, $ePaysArrive, $eAeroportArrive, $eAjouter));

	} // init()

} // Application_Form_Connexion