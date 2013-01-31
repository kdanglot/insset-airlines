<?php
class Application_Model_DbTable_Reservation extends Zend_Db_Table_Abstract {
	protected $_name = 'reservations';
	protected $primary = 'RES_id';
	
    protected $_dependentTables = 'Application_Model_DbTable_PlacesReservees';
	
	protected $_referenceMap = array (
			'AgenceDeVoyage' => array(
					'columns' => 'AGE_id',
					'refColumns' => 'AGE_id',
					'refTableClass' => 'Application_Model_DbTable_AgenceDeVoyage'
			)
	);

	public function getReservations(){
		$reservationsListe = $this->fetchAll();
		$reservationTab = array();
		$i = 0;
		
		foreach ($reservationsListe as $reservation) {
			if (!$reservation->RES_validee) {
				$dateDebut = new DateTime($reservation->RES_dateDebut);
				$dateFin = $dateDebut->add(new DateInterval('PT2H'));
				$now = new DateTime("now");
				if ($dateFin > $now) {
					$reservationTab[$i]["RES_id"] = $reservation->RES_id;
					$reservationTab[$i]["dateFin"] = $dateFin->format("F d, Y H:i:s");
					$agenceDeVoyage = $reservation->findParentApplication_Model_DbTable_AgenceDeVoyage();
					$reservationTab[$i]["agenceDeVoyage"]["AGE_id"] = $agenceDeVoyage->AGE_id;
					$reservationTab[$i]["agenceDeVoyage"]["AGE_nom"] = $agenceDeVoyage->AGE_nom;
					$places = $reservation->findApplication_Model_DbTable_PlacesReservees();
					$reservationTab[$i]["nbPlace"] = count($places);
					$i++;
				}
			}
		}
		
		return $reservationTab;
	}
	
	public function finirReservation($idReservation){
		$reservation = $this->find($idReservation)->current();
		
		$reservation->RES_validee = 1;
		$reservation->save();
		
		$places = $reservation->findApplication_Model_DbTable_PlacesViaApplication_Model_DbTable_PlacesReservees();
		foreach ($places as $place) {
			$place->AGE_id = $reservation->AGE_id;
			$place->PLA_statut = 1;
			$place->save();
		}
		
		return $place->VOL_id;
	}
	
	public function ajouterReservation($idAgenceDeVoyage, $nbPlacesVoulu, $idVol) {
		$auth = Zend_Auth::getInstance();
		$identity = $auth->getIdentity();
		
		//Creation de la reservation.
		$reservation = $this->createRow();
		$reservation->AGE_id = $idAgenceDeVoyage;
		$reservation->UTI_id_serviceCommercial = $identity->UTI_id;
		$reservation->RES_dateDebut	= date("Y-m-d H:i:s");
		$reservation->RES_validee = 0;
		$idReservation = $reservation->save();
		
		$placesReserveesTable = new Application_Model_DbTable_PlacesReservees();
		$placesTable = new Application_Model_DbTable_Places();
		
		//On trouve les places associées aux vol voulu.
		$placesVol = $placesTable->fetchAll($placesTable->select()->from("places")->where("VOL_id = ?", $idVol));
		$i = 0;
		
		foreach ($placesVol as $place) {
			//Rexuperation du ou des reservations des places
			$placeReservees = $place->findApplication_Model_DbTable_PlacesReservees();
			
			if (count($placeReservees) != 0) {
				foreach ($placeReservees as $placeReservee) {
					$reservee = false;
					$reservation = $placeReservee->findParentApplication_Model_DbTable_Reservation();
					$dateDebutReservation = new DateTime($reservation->RES_dateDebut);
					$dateFinReservation = $dateDebutReservation->add(new DateInterval('PT2H'));
					$now = new DateTime("now");
					//Si la place n'est pas déja reservées, on la prend.
					if($reservation->RES_validee == 1 || $dateFinReservation > $now){
						$reservee = true;
					}
				}
				if (!$reservee) {
					if ($i < $nbPlacesVoulu) {
						$placesReservee = $placesReserveesTable->createRow();
						$placesReservee->RES_id = $idReservation;
						$placesReservee->PLA_id = $place->PLA_id;
						$placesReservee->save();
						$i++;
					}else{
						break 1;
					}
				}
			}else{
				if ($i < $nbPlacesVoulu) {
					$placesReservee = $placesReserveesTable->createRow();
					$placesReservee->RES_id = $idReservation;
					$placesReservee->PLA_id = $place->PLA_id;
					$placesReservee->save();
					$i++;
				}else{
					break 1;
				}
			}
		}
	}
	
	public function placeReservee($place){
			//Rexuperation du ou des reservations des places
			$placeReservees = $place->findApplication_Model_DbTable_PlacesReservees();
			
			if (count($placeReservees) != 0) {
				foreach ($placeReservees as $placeReservee) {
					$reservation = $placeReservee->findParentApplication_Model_DbTable_Reservation();
					$dateDebutReservation = new DateTime($reservation->RES_dateDebut);
					$dateFinReservation = $dateDebutReservation->add(new DateInterval('PT2H'));
					$now = new DateTime("now");
					//Si la place n'est pas déja reservées, on la prend.
					if($reservation->RES_validee == 1 || $dateFinReservation > $now){
						return true;
					}
				}
				return false;
			}else{
				return false;
			}
	}
}
