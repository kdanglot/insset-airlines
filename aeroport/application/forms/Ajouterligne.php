<?php

class Application_Form_Ajouterligne extends Zend_Form {

	public function init() {
		 
		$this->setMethod('post');
		$this->setAttrib('id', 'ajouterLigneFormulaire');
		 
		// element Text heure de depart + attributs
		$eHeureDepart = new Zend_Form_Element_Text('heureDepart');
		$eHeureDepart->setAttrib('placeholder', 'heureDepart');
		$eHeureDepart->setAttrib('autofocus', 'autofocus');
		$eHeureDepart->setLabel('Heure de départ : ');
		$eHeureDepart->setRequired(true);
		$eHeureDepart->addFilter('StringTrim');
		$eHeureDepart->addValidator('NotEmpty');

		// element Text duree + attributs
		$eDuree = new Zend_Form_Element_Text('duree');
		$eDuree->setAttrib('placeholder', 'duree');
		$eDuree->setLabel('Durée : ');
		$eDuree->setRequired(true);
		$eDuree->addFilter('StringTrim');
		$eDuree->addValidator('NotEmpty');
		
		// element Text aeroport de depart + attributs
		$eAeroportDepart = new Zend_Form_Element_Text('aeroportDepart');
		$eAeroportDepart->setLabel("Aéroport de départ :");
		$eAeroportDepart->setRequired(true);
		$eAeroportDepart->addFilter('StringTrim');
		$eAeroportDepart->addValidator('NotEmpty');
		
		// element Text aeroport d'arrive + attributs
		$eAeroportArrive = new Zend_Form_Element_Text('aeroportArrive');
		$eAeroportArrive->setLabel("Aéroport d'arrivé :");
		$eAeroportArrive->setRequired(true);
		$eAeroportArrive->addFilter('StringTrim');
		$eAeroportArrive->addValidator('NotEmpty');
		
		// element Select periodicite + attributs
		$ePeriodicite = new Zend_Form_Element_Select('periodicite');
		$ePeriodicite->setLabel('Periodicite : ');
		$ePeriodicite->addMultiOptions(array('unique'=>'unique','journalier'=>'journalier','hebdomadaire'=>'hebdomadaire','mensuel'=>'mensuel','annuel'=>'annuel'));
		

		// element Submit connexion + attributs
		$eAjouter = new Zend_Form_Element_Submit('ajouter');
		$eAjouter->setAttrib('id', 'boutonAjouter');

		// ajout des elements au formulaire
		$this->addElements(array($eHeureDepart, $eDuree, $ePeriodicite, $eAeroportDepart, $eAeroportArrive, $eAjouter));

	} // init()

} // Application_Form_Connexion