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
		
		$tabNomJours = array('lun', 'mar', 'mer', 'jeu', 'ven', 'sam', 'dim');
		
		$tabJours = array();
		
		// Parcourir les jours
		$jourSemaine = 0;
		foreach($this->planning as $jour => $lignes){
			
			$jour ++; // Le tableau des jours commence à l'index 0. On le fais donc commencer à l'index 1.
			$dayVol = $this->dateDepart; // Le jour véritable du vol (ex : 08, 14, 31, ...)
			$dayStart = intval($this->dateDepart->format('w')); // Le numéro du jour de départ de l'affichage
			
			// Si c'est un jour passé (ex : $dayStart = 
			if($dayStart > $jour){ 
				// Retire le nombre de jour nécessaire au DateTime en partant de la date d'aujourd'hui
				$dayVol->sub(new DateInterval('P' . ($dayStart - $jour) . 'D'));
			}
			// Sinon c'est un jour futur
			else {
				// Donc on ajoute les jours nécessaires
				$dayVol->add(new DateInterval('P' . ($jour - $dayStart) . 'D'));
			}
			
			$dayVol = $dayVol->format('d');
			
			// Parcourir les lignes
			foreach($lignes as $ligne => $vols){
				
				// Parcourir les vols
				foreach($vols as $vol){
					
					$tabJours[$dayVol] = array(
						'dayComplete' => $tabNomJours[$jourSemaine] . ' ' . $dayVol
					);
					
				}
			}
			
			$jourSemaine++;
			if($jourSemaine == 7) { $jourSemaine = 0; }
			
		}
		
		$this->view->dateDepart = $dateDepart;
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
