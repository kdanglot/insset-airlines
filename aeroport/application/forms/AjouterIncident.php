<?php
class Application_Form_AjouterIncident extends Zend_Form {
	
	public function init() {
		$eDate = new Zend_Form_Element_Text('dateIncident');
		$eDate->setAttrib('placeholder', 'Date de l\'incident');
		
		$eIncident = new Zend_Form_Element_Select('typeIncident');
		$eIncident->addMultiOption('-1', 'Choix de l\'incident');
		
		$eBouton = new Zend_Form_Element_Submit('valider');
		
		$this->addElements(array($eDate, $eIncident, $eBouton));
	}
}