<?php

class Application_Form_PlanifierVol extends Zend_Form {

	public function init() {
		$db = Zend_Registry::get('db');
		
		$avions = new Application_Model_DbTable_Avion();
		$pilotes = new Application_Model_DbTable_Pilote();
		 
		$this->setMethod('post');
		$this->setAttrib('id', 'planifierVolForm');
		
		// element hidden idVol
		$eIdVol = new Zend_Form_Element_Hidden('id');
        $eIdVol->addFilter('Int');
		
		// element hidden idLigne
		$eIdLigne = new Zend_Form_Element_Hidden('ligne');
        $eIdLigne->addFilter('Int');
		 
		// element Text heure de depart + attributs
		$eHeureDepart = new Zend_Form_Element_Text('dateDepart');
		$eHeureDepart->setAttrib('placeholder', 'Date de départ Effective');
		$eHeureDepart->setLabel('Date de départ Effective : ');
		$eHeureDepart->addFilter('StringTrim');

		// element Text heure d'arrive + attributs
		$eHeureArrivee = new Zend_Form_Element_Text('dateArrivee');
		$eHeureArrivee->setAttrib('placeholder', "Date d'arrivée Effective");
		$eHeureArrivee->setLabel("Date d'arrivée Effective : ");
		$eHeureArrivee->addFilter('StringTrim');
		
		// Liste des Avions
		$eAvion = new Zend_Form_Element_Select('avion');
		$eAvion->setLabel("Avion");
		$eAvion->setRequired(true);
		$eAvion->options = $avions->afficherListeAvionsDisponibles();
		
		// Liste des Pilotes
		$ePilote = new Zend_Form_Element_Select('pilote');
		$ePilote->setLabel("Pilote");
		$ePilote->setRequired(true);
		$ePilote->options = $pilotes->afficherListePilotesDisponibles();
		
		// Liste des Copilotes
		$eCopilote = new Zend_Form_Element_Select('copilote');
		$eCopilote->setLabel("Copilote");
		$eCopilote->setRequired(true);
		$eCopilote->options = $pilotes->afficherListePilotesDisponibles();
		
		// element Text aéroport départ + attributs
		$eAeroportDepart = new Zend_Form_Element_Hidden('aeroportDepart');
        $eAeroportDepart->addFilter('Int');
		
		// element Text aéroport d'arrivée + attributs
		$eAeroportArrivee = new Zend_Form_Element_Hidden('aeroportArrivee');
        $eAeroportArrivee->addFilter('Int');

		// element Submit connexion + attributs
		$ePlanifier = new Zend_Form_Element_Submit('Planifier');
		$ePlanifier->setAttrib('id', 'boutonAjouter');

		// ajout des elements au formulaire
		$this->addElements(array($eIdVol, $eIdLigne, $eAeroportDepart, $eAeroportArrivee, $eHeureDepart, $eHeureArrivee, $eAvion, $ePilote, $eCopilote, $ePlanifier));

	}

} 
