<?php 
echo "<script>alert('test')</script>";
echo '<select name="a1">';
	if(isset($_POST["idPays"])){
 		$db = Zend_Registry::get('db');
		$aeroport = new Application_Model_DbTable_Aeroport();
		$aeroports = $aeroport->aeroportPays($_POST["idPays"]);
		foreach($aeroports as $a) {
			echo "<option value='".$a["AER_id"]."'>".$a["AER_nom"]."</option>";
		}
	}
echo "</select>";
