<?php
class Application_Form_AjouterIncident extends Zend_Form {
	
	public function init() {
		$typeIncident = new Application_Model_DbTable_TypeIncident();
		$listeTypeIncident = $typeIncident->getTypeIncident();
		
		$eIncident = new Zend_Form_Element_Select('typeIncident');
		$eIncident->setLabel('Incident');
		foreach ($listeTypeIncident as $incident) {
			$eIncident->addMultiOption($incident['TINC_id'], $incident['TINC_nom']);
		}
		
		$eBouton = new Zend_Form_Element_Submit('valider');
		
		$this->addElements(array($eIncident, $eBouton));
	}
}