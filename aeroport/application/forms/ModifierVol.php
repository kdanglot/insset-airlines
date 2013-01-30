<?php
class Application_Form_ModifierVol extends Zend_Form {

	public function init() {
		$this->setMethod('post');
		$this->setAttrib('id', 'modifierVolForm');
		
		// on instancie un objet AVION
		$avions = new Application_Model_DbTable_Avion();
		// on instancie un objet PILOTE
		$pilotes = new Application_Model_DbTable_Pilote();
		
		// element hidden idVol
		$eIdVol = new Zend_Form_Element_Hidden('id');
		$eIdVol->addFilter('Int');
		
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
		
		// Heure de départ effective
		$eHeureDepartEffective = new Zend_Form_Element_Text('heureDepartEffective');
		$eHeureDepartEffective->setLabel('Heure de départ effective');
		
		// Heure de d'arrivée effective
		$eHeureArriveeEffective = new Zend_Form_Element_Text('heureArriveeEffective');
		$eHeureArriveeEffective->setLabel('Heure d\'arrivée effective');
		
		$eModifier = new Zend_Form_Element_Submit('modifier');
		$eModifier->setAttrib('id', 'boutonModifier');
		
		// ajout des elements au formulaire
		$this->addElements(array($eIdVol, $eAvion, $ePilote, $eCopilote, $eHeureDepartEffective, $eHeureArriveeEffective, $eModifier));
	}
}