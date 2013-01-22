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

		$dateDepart = new DateTime();
		// $dateDepart = $dateDepart->sub(new DateInterval('P1W'));
		$volsListeBrute = $vols->afficherVolPlanning($dateDepart, 1);
		// echo '<pre>'; var_dump($volsListeBrute); exit;
		$tabNomJours = array('lun', 'mar', 'mer', 'jeu', 'ven', 'sam', 'dim');
		$planning = array();

		// Parcourir les jours
		$jourSemaine = 0;
		foreach($volsListeBrute as $jour => $lignes){

			$jour ++; // Le tableau des jours commence à l'index 0. On le fais donc commencer à l'index 1.
			$dayVol = $dateDepart; // Le jour véritable du vol (ex : 08, 14, 31, ...)
			$dayStart = intval($dateDepart->format('w')); // Le numéro du jour de départ de l'affichage

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

			$dayVol = $dayVol->format('d');

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
						$dateDepartVol = $tabNomJours[intval(date_format($dateDepartVol, 'N')) - 1] . ' ' . date_format($dateDepartVol, 'd') . ' '. date_format($dateDepartVol, 'H:i');	
						$class = 'planfie';
					}
					else{
						$dateDepartVol = $tabNomJours[$jourSemaine] . ' ' . $dayVol;
						$class = 'non-planifie';
					}

					// Formater la date d'arrivée si elle existe
					if(isset($vol['VOL_dateArriveeEffective'])){ 
						$dateArrivee = DateTime::createFromFormat('Y-m-d H:i:s', $vol['VOL_dateArriveeEffective']);
						$dateArrivee = $tabNomJours[intval(date_format($dateArrivee, 'N')) - 1] . ' ' . date_format($dateArrivee, 'd') . ' '. date_format($dateArrivee, 'H:i');					
					}
					else{
						$dateArrivee = '';
					}

					// Créer un avion vide s'il n'y en a pas
					if(!isset($vol['avion'])){
						$vol['avion'] = array(
							'AVI_id' => '',
							'AVI_immatriculation' => ''
						);
					}

					// Créer un pilote vide s'il n'y en a pas
					if(!isset($vol['pilote'])){
						$pilote = array(
							'id' => '',
							'nom' => ''
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
							'id' => '',
							'nom' => ''
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
						'trajet' => $trajet,
						'depart' => array(
							'date' => $dateDepartVol,
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
				$idVol = $formPlanifier->getValue('idVol');
				$ligne = $formPlanifier->getValue('idLigne');
				$dateDepart = $formPlanifier->getValue('dateDepart');
				$aeroportDepart = $formPlanifier->getValue('aeroportDepart');
				$dateArrivee = $formPlanifier->getValue('dateArrivee');
				$aeroportArrivee = $formPlanifier->getValue('aeroportArrivee');
				$avion = $formPlanifier->getValue('avion');
				$pilote = $formPlanifier->getValue('pilote');
				$copilote = $formPlanifier->getValue('copilote');
			
				$vol = new Application_Model_DbTable_Vol();
				
				// Si le vol existe on le modifie
				if(isset($idVol)){
					$vol->modifier($idVol, $ligne, $dateDepart, $aeroportDepart, $dateArrivee, $aeroportArrivee, $avion, $pilote, $copilote);
				}
				
				// S'il n'existe pas on le créé avec ces données
				else{
					$vol->creer($ligne, $dateDepart, $aeroportDepart, $dateArrivee, $aeroportArrivee, $avion, $pilote, $copilote);
				}
				
				// Après les modifications faites on revient à l'index
				$this->_helper->redirector('index');
			}
			
			// sinon on le réaffiche avec les données
			else{
				$formPlanifier->populate($formData);
			}
		
		}
		
		// Sinon on affiche le formulaire avec les données
		else{
			
			$idVol = $this->_getParam('idVol', 0);
			$ligne = $this->_getParam('idLigne', 0);
			$aeroportDepart = $this->_getParam('aeroportDepart', 0);
			$aeroportArrivee = $this->_getParam('aeroportArrivee', 0);
			
			// Si le vol existe on affiche ses données
			if($idVol){
			
				// Récupérer les données
				$vol = new Application_Model_DbTable_Vol();
				
				$dataVol = $vol->getVol($idVol);var_dump($dataVol);
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
			
			// Sinon on le créé avec des données générées
			else{
			
				// Récupérer les données
				$vol = new Application_Model_DbTable_Vol();
				// $vol->getVolFictif($ligne, $aeroportDepart);
				// $formPlanifier->populate($vol);
			}

		}
		
	}
	
}
