<?php

class Application_Form_AjouterModifierligne extends Zend_Form {

	public function init() {
		$db = Zend_Registry::get('db');
		$aeroport = new Application_Model_DbTable_Aeroport();
		$aeroports = $aeroport->afficherLesAeroports();
		 
		$this->setMethod('post');
		$this->setAttrib('id', 'ajouterLigneFormulaire');
		
		// element Hidden idLigne + attributs
		$eIdLigne = new Zend_Form_Element_Hidden('idLigne');
			$eIdLigne->addValidator('NotEmpty');
			$eIdLigne->addFilter('Int');
		 
		// element Text heure de depart + attributs
		$eHeureDepart = new Zend_Form_Element_Text('heureDepart');
			$eHeureDepart->setAttrib('placeholder', 'heureDepart');
			$eHeureDepart->setAttrib('autofocus', 'autofocus');
			$eHeureDepart->setAttrib('required', 'required');
			$eHeureDepart->setLabel('Heure de départ : ');
			$eHeureDepart->setRequired(true);
			$eHeureDepart->addFilter('StringTrim');
			$eHeureDepart->addValidator('NotEmpty');

		// element Text heure d'arrive + attributs
		$eHeureArrivee = new Zend_Form_Element_Text('heureArrivee');
			$eHeureArrivee->setAttrib('placeholder', "Heure d'arrivée");
			$eHeureArrivee->setAttrib('required', 'required');
			$eHeureArrivee->setLabel("Heure d'arrivée");
			$eHeureArrivee->setRequired(true);
			$eHeureArrivee->addFilter('StringTrim');
			$eHeureArrivee->addValidator('NotEmpty');
		
		// element Select pays de depart + attributs
		$ePaysDepart = new Zend_Form_Element_Select('paysDepart');
			$ePaysDepart->setLabel("Pays de départ :");
			/*foreach($pays as $p) {
				$ePaysDepart->addMultiOption($aeroport['AER_id'], $aeroport['AER_nom']);
			}*/
		
		// element Text aeroport de depart + attributs
		$eAeroportDepart = new Zend_Form_Element_Select('aeroportDepart');
			$eAeroportDepart->setLabel("Aéroport de départ :");
			foreach($aeroports as $aeroport) {
				$eAeroportDepart->addMultiOption($aeroport['AER_id'], $aeroport['AER_nom']);
			}
			$eAeroportDepart->setRequired(true);	
		
		// element Select pays d'arrivé + attributs
		$ePaysArrive = new Zend_Form_Element_Select('paysArrive');
			$ePaysArrive->setLabel("Pays d'arrivé :");
			/*foreach($pays as $p) {
			 $ePaysArrive->addMultiOption($aeroport['AER_id'], $aeroport['AER_nom']);
			}*/
		
		// element Text aeroport d'arrive + attributs
		$eAeroportArrive = new Zend_Form_Element_Select('aeroportArrive');
			$eAeroportArrive->setLabel("Aéroport d'arrivé :");
			foreach($aeroports as $aeroport) {
				$eAeroportArrive->addMultiOption($aeroport['AER_id'], $aeroport['AER_nom']);
			}
			$eAeroportArrive->setRequired(true);
		
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