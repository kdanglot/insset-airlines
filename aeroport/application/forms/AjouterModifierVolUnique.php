<?php

class Application_Form_AjouterModifierVolUnique extends Zend_Form {

	public function init() {
		$db = Zend_Registry::get('db');
		$aeroport = new Application_Model_DbTable_Aeroport();
		$aeroports = $aeroport->afficherLesAeroports();
		$pays = new Application_Model_DbTable_Pays();
		$lesPays = $pays->afficherLesPays();
			 
		$this->setMethod('post');
		$this->setAttrib('id', 'ajouterLigneFormulaire');
		 
		// element Text heure de depart + attributs
		$heureDepart = new Zend_Form_Element_Text('dateDepart');
		$heureDepart->setAttrib('placeholder', 'heure Départ');
		$heureDepart->setAttrib('autofocus', 'autofocus');
		$heureDepart->setLabel('Heure de départ : ');
		$heureDepart->setRequired(true);
		$heureDepart->addFilter('StringTrim');
		$heureDepart->setAttrib('required', 'required');
		$heureDepart->addValidator(new Zend_Validate_Date(array('format' => 'YY-mm-dd HH:ii:ss')));
		
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
		//$ePaysArrive->setRequired(true);
		
		// element Text aeroport d'arrive + attributs
		$eAeroportArrive = new Zend_Form_Element_Select('aeroportArrive');
		$eAeroportArrive->setLabel("Aéroport d'arrivé :");
		$eAeroportArrive->setAttrib('name', 'aeroport-arrive');
		$eAeroportArrive->setAttrib('id', 'aeroport-arrive');
		$eAeroportArrive->addMultiOption('-1', 'Choisissez un aeroport');
			
		$avions = new Application_Model_DbTable_Avion();
		// Liste des Avions
		$eAvion = new Zend_Form_Element_Select('avion');
		$eAvion->setLabel("Avion");
		$eAvion->setRequired(true);
		$eAvion->options = $avions->afficherListeAvionsDisponibles();
		
		// element Submit connexion + attributs
		$eAjouter = new Zend_Form_Element_Submit('ajouter');
		$eAjouter->setAttrib('id', 'boutonAjouter');


		// ajout des elements au formulaire
		$this->addElements(array($heureDepart, $ePaysDepart, $eAeroportDepart, $ePaysArrive, $eAeroportArrive, $eAvion, $eAjouter));

	} // init()

} // Application_Form_Connexion
