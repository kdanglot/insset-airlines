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
		$this->view->vols = $vols->getVols();
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
				$ligne = $formPlanifier->getValue('ligne');
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
			$ligne = $this->_getParam('ligne', 0);
			$aeroportDepart = $this->_getParam('aeroportDepart', 0);
			$aeroportArrivee = $this->_getParam('aeroportArrivee', 0);
			
			
			// Si le vol existe on affiche ses données
			if(isset($idVol)){
				
				// Récupérer les données
				$vol = new Application_Model_DbTable_Vol();
				$vol->getVol($idVol);
				$formPlanifier->populate($vol);
				
			}
			
			// Sinon on le créé avec des données générées
			else{
				
			}

		}
		
	}
	
}

