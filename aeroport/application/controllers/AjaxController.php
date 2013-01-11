<?php
class AjaxController extends Zend_Controller_Action {
	
	public function aeroportbypaysAction() {
		if ($this->_request->isXmlHttpRequest()) {
			$idPays = $this->_request->getParam('idPays');
			$aeroport = new Application_Model_DbTable_Aeroport();
			$list = $aeroport->aeroportPays($idPays);
			/*$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender(true);
			$this->_helper->viewRenderer->setNeverRender(true);*/
			
			// header('content-type: application/json');
			//résultat qui est récupéré par l'Ajax
			$this->_helper->json($list);		
		}
		else {
			$this->_redirect('/index/index');
		}
	}
}