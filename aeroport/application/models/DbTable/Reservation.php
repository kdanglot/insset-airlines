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
	}
}
