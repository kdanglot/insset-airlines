<?php

class Application_Form_CreerVol extends Zend_Form {

	public function init() {
		$db = Zend_Registry::get('db');
		
		$avions = new Application_Model_DbTable_Avion();
		$pilotes = new Application_Model_DbTable_Pilote();
		 
		$this->setMethod('post');
		$this->setAttrib('id', 'planifierVolForm');
		
		// element hidden idLigne
		$eIdLigne = new Zend_Form_Element_Hidden('ligne');
        $eIdLigne->addFilter('Int');
		 
		// heure Départ
		$eHeureDepart = new Zend_Form_Element_Text('heureDepartPrevue');
		$eHeureDepart->setAttrib('placeholder', 'Heure de départ Prévue');
		$eHeureDepart->setLabel('Heure de départ Prévue : ');
		$eHeureDepart->setRequired(true);
		$eHeureDepart->addFilter('StringTrim');
		$eHeureDepart->addValidator('NotEmpty');
		
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
		
		// Date Départ
		$eDateDepart = new Zend_Form_Element_Hidden('dateDepart');
        $eDateDepart->addFilter('Int');

		// element Submit connexion + attributs
		$ePlanifier = new Zend_Form_Element_Submit('Planifier');
		$ePlanifier->setAttrib('id', 'boutonAjouter');

		// ajout des elements au formulaire
		$this->addElements(array($eIdLigne, $eAeroportDepart, $eAeroportArrivee, $eDateDepart, $eHeureDepart, $eAvion, $ePilote, $eCopilote, $ePlanifier));

	}

} 
