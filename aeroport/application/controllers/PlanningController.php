<?php

class PlanningController extends Zend_Controller_Action
{
 public function init() {
    	$auth = Zend_Auth::getInstance();
    	$identity = $auth->getIdentity();
    	// $typeUtilisateur = $identity->UTI_typeEmploye;
    	
    	// /*if('administrateur' != $typeUtilisateur) {
    		// $this->_redirect('index/index');
    	// }*/
    }

    public function indexAction() {
    	
		$vols = new Application_Model_DbTable_Vol();
		
		$tabNomJours = array('lun', 'mar', 'mer', 'jeu', 'ven', 'sam', 'dim');
		
		// Récupérer les semaines du premier vol jusqu'à aujourd'hui
		$tabJours = array();
		$dernierVol = new DateTime();
		$premierVol = new DateTime($vols->getFirstDateDepart());
		
		$premierVol->sub(new DateInterval('P' . (intval($premierVol->format('w')) - 1) . 'D'));
		$dernierVol->add(new DateInterval('P' . (intval($dernierVol->format('w')) - 1) . 'D'));
		$dernierVol->add(new DateInterval('P4W'));
		
		$dernierVolStr = $dernierVol->format('Y-W');
		
		$semainePre = 0;
		$anneePre = 0;
		
		while($premierVol->format('Y-W') <= $dernierVolStr){
			
			$annee = intval($premierVol->format('Y'));
			$semaine = intval($premierVol->format('W'));
			$jour = intval($premierVol->format('d'));
			$numJourSemaine = intval($premierVol->format('w'));
			
			
			if($semaine == $semainePre and $annee != $anneePre){
				$annee = $anneePre;
			}
			if(!isset($tabJours[$annee . '-' . $semaine])){
				$tabJours[$annee . '-' . $semaine] = array();
			}
			$tabJours[$annee . '-' . $semaine][] = $tabNomJours[$numJourSemaine] . ' ' . $jour;
			
			$premierVol->add(new DateInterval('P1D'));
			$semainePre = $semaine;
			$anneePre = $annee;
		}
		// var_dump($tabJours);exit;
		$this->view->panel = $tabJours;
		
		if($this->_getParam('date') != ''){
			$dateDepart = DateTime::createFromFormat('YmdHis', $this->_getParam('date'));
		}
		else{
			$dateDepart = new DateTime();
		}
		if($dateDepart == false){
			$dateDepart = new DateTime();
		}
		$volsListeBrute = $vols->afficherVolPlanning($dateDepart, $this->_getParam('week', 5));
		// var_dump($volsListeBrute);exit;
		
		$tabNomMois = array('jan', 'fév', 'mar', 'avri', 'juin', 'juil', 'août', 'sep', 'oct', 'nov', 'déc');
		$planning = array();

		// Parcourir les jours
		$jourSemaine = 0;
		foreach($volsListeBrute as $jour => $lignes){

			$jour ++; // Le tableau des jours commence à l'index 0. On le fais donc commencer à l'index 1.
			$dayVol = clone $dateDepart; // Le jour véritable du vol (ex : 08, 14, 31, ...)
			$dayStart = intval($dayVol->format('w')); // Le numéro du jour de départ de l'affichage

			// Comme la méthode renvoie la semaine complète
			// Si on envoie un Jeudi en jour de départ
			// Le tableau renvoyé correspondra à un mardi
			// Il faut donc comparer le jour d'aujourd'hui et le tableau des jours renvoyé par la méthode (qui commence à 1) pour résoudre le numéro véritable des jours.
			// Si c'est un jour passé
			
			if($dayStart > $jour){
				// Retire le nombre de jour nécessaire
				$dayVol->sub(new DateInterval('P' . ($dayStart - $jour) . 'D'));
			}
			// Sinon c'est un jour futur
			else {
				// ajoute les jours nécessaires
				$dayVol->add(new DateInterval('P' . ($jour - $dayStart) . 'D'));
			}

			// Parcourir les lignes
			foreach($lignes as $ligne => $vols){

				// Parcourir les vols
				foreach($vols as $trajet => $vol){

					if(!isset($vol['VOL_id'])){
						$vol['VOL_id'] = 0;
					}

					// Formater la date de départ si elle existe
					if(isset($vol['VOL_dateDepartEffective'])){
						$dateDepartVol = DateTime::createFromFormat('Y-m-d H:i:s', $vol['VOL_dateDepartEffective']);
						$dateDepartEff = $tabNomJours[intval(date_format($dateDepartVol, 'N')) - 1] . ' ' . date_format($dateDepartVol, 'd') . ' ' . $tabNomMois[($dayVol->format('n') - 1)] . ' '. date_format($dateDepartVol, 'H:i');	
						$class = 'planfie';
					}
					else{
						$dateDepartEff = '-';
						$class = 'non-planifie';
					}
					
					// Formater la date de départ prévue si elle existe
					if(isset($vol['VOL_dateDepartEffective'])){
						$dateDepartVol = DateTime::createFromFormat('Y-m-d H:i:s', $vol['VOL_dateDepartPrevue']);
						$dateDepartPrevu = $tabNomJours[intval(date_format($dateDepartVol, 'N')) - 1] . ' ' . date_format($dateDepartVol, 'd') . ' ' . $tabNomMois[($dayVol->format('n') - 1)] . ' '. date_format($dateDepartVol, 'H:i');	
					}
					else{
						$dateDepartPrevu = $tabNomJours[$jourSemaine] . ' ' . $dayVol->format('d') . ' ' . $tabNomMois[($dayVol->format('n') - 1)];
					}
					$dateDepartUrl = $dayVol->format('Ymd');
					
					// Formater la date d'arrivée si elle existe
					if(isset($vol['VOL_dateArriveeEffective'])){ 
						$dateArrivee = DateTime::createFromFormat('Y-m-d H:i:s', $vol['VOL_dateArriveeEffective']);
						$dateArrivee = $tabNomJours[intval(date_format($dateArrivee, 'N')) - 1] . ' ' . date_format($dateArrivee, 'd') . ' '. date_format($dateArrivee, 'H:i');					
					}
					else{
						$dateArrivee = '-';
					}

					// Créer un avion vide s'il n'y en a pas
					if(!isset($vol['avion'])){
						$vol['avion'] = array(
							'AVI_id' => '-',
							'AVI_immatriculation' => '-'
						);
					}

					// Créer un pilote vide s'il n'y en a pas
					if(!isset($vol['pilote'])){
						$pilote = array(
							'id' => '-',
							'nom' => '-'
						);
					}
					else{
						$pilote = array(
							'id' => $vol['pilote']['PIL_id'],
							'nom' => $vol['pilote']['utilisateur']['UTI_nom'] . ' ' . $vol['pilote']['utilisateur']['UTI_prenom']
						);
					}

					// Créer un copilote vide s'il n'y en a pas
					if(!isset($vol['coPilote'])){
						$copilote = array(
							'id' => '-',
							'nom' => '-'
						);
					}
					else{
						$copilote = array(
							'id' => $vol['coPilote']['PIL_id'],
							'nom' => $vol['coPilote']['utilisateur']['UTI_nom'] . ' ' . $vol['coPilote']['utilisateur']['UTI_prenom']
						);
					}

					$planning[] = array(
						'id' => $vol['VOL_id'],
						'class' => $class,
						'depart' => array(
							'date' => $dateDepartEff,
							'prevu' => $dateDepartPrevu,
							'dateurl' => $dateDepartUrl,
							'idAeroport' => intval($vol['aeroportDepart']['AER_id_depart']),
							'nomAeroport' => $vol['aeroportDepart']['AER_nom']
						),
						'arrivee' => array(
							'date' => $dateArrivee,
							'idAeroport' => intval($vol['aeroportArrivee']['AER_id_arrivee']),
							'nomAeroport' => $vol['aeroportArrivee']['AER_nom']
						),
						'ligne' => $ligne,
						'avion' => array(
							'id' => $vol['avion']['AVI_id'],
							'immatriculation' => $vol['avion']['AVI_immatriculation']
						),
						'pilote' => $pilote,
						'copilote' => $copilote
					);

				}
			}

			$jourSemaine++;
			if($jourSemaine == 7) { $jourSemaine = 0; }

		}
		// echo '<pre>'; var_dump($planning); exit;
		$this->view->planning = $planning;
    }
	
	public function creerAction() {
		
		$formCreer = new Application_Form_CreerVol();
		$this->view->formCreer = $formCreer;
		
		// Si on a reçu une requête avec des données POST
		if($this->getRequest()->isPost()) {
		
			$formData = $this->getRequest()->getPost();
			
			// Si les données reçues sont valides pour ce formulaire
			if($formCreer->isValid($formData)) {
			
				// Récupérer les données
				$ligne = $formCreer->getValue('ligne');
				$dateDepart = $formCreer->getValue('dateDepart');
				$heureDepart = $formCreer->getValue('heureDepartPrevue');
				$aeroportDepart = $formCreer->getValue('aeroportDepart');
				$aeroportArrivee = $formCreer->getValue('aeroportArrivee');
				$avion = $formCreer->getValue('avion');
				$pilote = $formCreer->getValue('pilote');
				$copilote = $formCreer->getValue('copilote');
			
				$vol = new Application_Model_DbTable_Vol();
				$dateComposee = DateTime::createFromFormat('YmdH:i', ($dateDepart . $heureDepart));
				$vol->ajoutVol($ligne, $dateComposee->format('Y-m-d H:i:s'), $aeroportDepart, $aeroportArrivee, $avion, $pilote, $copilote);
				
				// Après les modifications faites on revient à l'index
				$this->_helper->redirector('index');
			}
			
			// sinon on le réaffiche avec les données
			else{
				$ligne = $formCreer->getValue('ligne');
				$dateDepart = $formCreer->getValue('date');
				$aeroportDepart = $formCreer->getValue('aeroportDepart');
				$aeroportArrivee = $formCreer->getValue('aeroportArrivee');
				
				// Récupérer les données
				$aeroports = new Application_Model_DbTable_Aeroport();
				$aeroportDepart = $aeroports->find($aeroportDepart)->current();
				$aeroportArrivee = $aeroports->find($aeroportArrivee)->current();
				
				$dataPopulate = array(
					'ligne' => $ligne,
					'dateDepart' => $dateDepart,
					'aeroportDepart' => $aeroportDepart->AER_id,
					'aeroportArrivee' => $aeroportArrivee->AER_id
				);
				
				$formCreer->populate($dataPopulate);
				
				$this->view->ligne = $ligne;
				$this->view->aeroportDepart = $aeroportDepart->AER_nom;
				$this->view->aeroportArrivee = $aeroportArrivee->AER_nom;
				$this->view->datePrevue = $dateDepart;
				$this->view->dateDepart = $dateDepart;
			}
		
		}
		
		// Sinon on affiche le formulaire avec les données de la BDD
		else{
			
			$ligne = $this->_getParam('ligne', 0);
			$dateDepart = $this->_getParam('date');
			$aeroportDepart = $this->_getParam('aeroportDepart');
			$aeroportArrivee = $this->_getParam('aeroportArrivee');
			
			// Récupérer les données
			$aeroports = new Application_Model_DbTable_Aeroport();
			$aeroportDepart = $aeroports->find($aeroportDepart)->current();
			$aeroportArrivee = $aeroports->find($aeroportArrivee)->current();
			
			$dataPopulate = array(
				'ligne' => $ligne,
				'dateDepart' => $dateDepart,
				'aeroportDepart' => $aeroportDepart->AER_id,
				'aeroportArrivee' => $aeroportArrivee->AER_id
			);
			
			$formCreer->populate($dataPopulate);
			
			$this->view->ligne = $ligne;
			$this->view->aeroportDepart = $aeroportDepart->AER_nom;
			$this->view->aeroportArrivee = $aeroportArrivee->AER_nom;
			$this->view->datePrevue = $dateDepart;
			$this->view->dateDepart = $dateDepart;
			
			$formCreer->getElement('avion')->setValue($formCreer->getValue('avion'));
			$formCreer->getElement('pilote')->setValue($formCreer->getValue('pilote'));
			$formCreer->getElement('copilote')->setValue($formCreer->getValue('copilote'));
			
		}
		
	}
	
	public function planifierAction() {
		
		// Récupérer le formulaire
		$formPlanifier = new Application_Form_PlanifierVol();
		
		// L'envoyer à la vue
		$this->view->formPlanifier = $formPlanifier;
		
		// Si on a reçu une requête avec des données POST
		if($this->getRequest()->isPost()) {
		
			$formData = $this->getRequest()->getPost();
			
			// Si les données reçues sont valides pour ce formulaire
			if($formPlanifier->isValid($formData)) {
			
				// Récupérer les données
				$idVol = $formPlanifier->getValue('id');
				$dateDepart = $formPlanifier->getValue('dateDepart');
				$aeroportDepart = $formPlanifier->getValue('aeroportDepart');
				$dateArrivee = $formPlanifier->getValue('dateArrivee');
				$aeroportArrivee = $formPlanifier->getValue('aeroportArrivee');
				$avion = $formPlanifier->getValue('avion');
				$pilote = $formPlanifier->getValue('pilote');
				$copilote = $formPlanifier->getValue('copilote');
			
				if($dateDepart == ''){ $dateDepart = null; }
				if($dateArrivee == ''){ $dateArrivee = null; }
			
				$vol = new Application_Model_DbTable_Vol();
				$vol->modifierVol($idVol, $dateDepart, $aeroportDepart, $dateArrivee, $aeroportArrivee, $avion, $pilote, $copilote);
				
				// Après les modifications faites on revient à l'index
				$this->_helper->redirector('index');
			}
			
			// sinon on le réaffiche avec les données
			else{
				$formPlanifier->populate($formData);
			}
		
		}
		
		// Sinon on affiche le formulaire avec les données de la BDD
		else{
			
			$idVol = $this->_getParam('id', 0);
			$ligne = $this->_getParam('idLigne', 0);
			$aeroportDepart = $this->_getParam('aeroportDepart', 0);
			$aeroportArrivee = $this->_getParam('aeroportArrivee', 0);
			
			// Récupérer les données
			$vol = new Application_Model_DbTable_Vol();
			
			$dataVol = $vol->getVol($idVol);
			// var_dump($dataVol);
			$dataPopulate = array(
				'id' => $dataVol['VOL_id'],
				'ligne' => $dataVol['LIG_id'],
				'dateDepart' => $dataVol['VOL_dateDepartEffective'],
				'dateArrivee' => $dataVol['VOL_dateArriveeEffective'],
				'aeroportDepart' => $dataVol['aeroportDepart']['AER_id_depart'],
				'aeroportArrivee' => $dataVol['aeroportArrivee']['AER_id_arrivee']
			);
			$formPlanifier->getElement('avion')->setValue($dataVol['avion']['AVI_id']);
			$formPlanifier->populate($dataPopulate);
			$this->view->ligne = $dataVol['LIG_id'];
			$this->view->aeroportDepart = $dataVol['aeroportDepart']['AER_nom'];
			$this->view->aeroportArrivee = $dataVol['aeroportArrivee']['AER_nom'];
			$this->view->datePrevue = $dataVol['VOL_dateDepartPrevue'];

		}
		
	}
	
}
