<?php
class Application_Form_AjouterMaintenance extends Zend_Form {
		
	public function init() {
		$view = Zend_Layout::getMvcInstance()->getView();
		$this->setAction($view->baseUrl('maintenance/creer'));
		
		$this->setMethod('POST');
		$this->setAttrib('id', 'ajouterMaintenance');
		
		$eDateEmbauche = new Zend_Form_Element_Text('dateDebutMaintenance');
		$eDateEmbauche->setAttrib('id', 'dateDebutMaintenance');
		$eDateEmbauche->setAttrib('required', 'required');
		$eDateEmbauche->setLabel('Date début: ');
		$eDateEmbauche->addValidator( new Zend_Validate_Date(array('format' => 'yyyy-mm-dd')));
		$eDateEmbauche->addValidator('NotEmpty');
		
		$eIdAvion = new Zend_Form_Element_Hidden('idAvionMaintenance');
		$eIdAvion->setAttrib('id', 'idAvionMaintenance');
		$eIdAvion->addValidator('NotEmpty');
		
		$eTypeMaintenance = new Zend_Form_Element_Hidden('typeMaintenance');
		$eTypeMaintenance->setAttrib('id', 'typeMaintenance');
		$eTypeMaintenance->addValidator('NotEmpty');
		
		$eValider = new Zend_Form_Element_Submit('valider');
		$eValider->setAttrib('id', 'valider');

		$this->addElements(array($eDateEmbauche, $eIdAvion, $eTypeMaintenance, $eValider));
	}
	
}