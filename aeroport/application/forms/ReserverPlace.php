<?php
class Application_Form_ReserverPlace extends Zend_Form {
	
	public function init() {
		
		$this->setMethod('POST');
		
		$agenceDeVoyageTable = new Application_Model_DbTable_AgenceDeVoyage();
		$agenceDeVoyages = $agenceDeVoyageTable->getAgenceDeVoyage();
		
		$eAgenceDeVoyage = new Zend_Form_Element_Select('agenceDeVoyage');
		$eAgenceDeVoyage->setLabel('agenceDeVoyage');
		
		foreach ($agenceDeVoyages as $agenceDeVoyage) {
			$eAgenceDeVoyage->addMultiOption($agenceDeVoyage['AGE_id'], $agenceDeVoyage['AGE_nom']);
		}
		
		$eNbPlace = new Zend_Form_Element_Hidden('nbPlacesVouluInput');
		$eNbPlace->setAttrib('id', 'nbPlacesVouluInput');
		$eNbPlace->setAttrib('required', 'required');
		$eNbPlace->setValue('0');
		$eNbPlace->addValidator('NotEmpty');
		
		$eBouton = new Zend_Form_Element_Submit('valider');
		
		$this->addElements(array($eNbPlace, $eAgenceDeVoyage, $eBouton));
	}
}