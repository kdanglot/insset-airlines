<?php
class AjaxController extends Zend_Controller_Action {

	public function init(){
	
		// Mettre en place le redirecteur
		$this->_redirector = $this->_helper->getHelper('Redirector');
		
		// Récupération ACL
		$acl = Zend_Registry::get('acl');
		
		// Récupération du rôle enregistré en session
		$session = new Zend_Session_Namespace('role');
		// var_dump($session->role);exit;
		$role = $session->role;
		$controller = $this->getRequest()->getControllerName();
		$action = $this->getRequest()->getActionName();
		
		// Vérification des droits
		if(!$acl->isAllowed($role, $controller, $action)){
			// Rediriger vers le controlleur adapté
			$this->_redirector->gotoUrl('/index/index/error/Vous devez d\'abord vous connecter');
		}
	
	}
	
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