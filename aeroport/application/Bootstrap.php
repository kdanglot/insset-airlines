<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {
	
	public function run() {
		parent::run();
	}
	
	protected function _initConfig() {
		
		Zend_Registry::set('configs', new Zend_Config($this->getOptions()));
		date_default_timezone_set ("Europe/Paris");
		
	}
	
	protected function _initSession() {
		$session = new Zend_Session_Namespace('projetAeroport', true);
		
		return $session;
		
	}
	
	protected function _initDb() {
		
		$db = Zend_Db::factory(Zend_Registry::get('configs')->database);
		Zend_Db_Table_Abstract::setDefaultAdapter($db);
		Zend_Registry::set('db', $db);
		
	} 
	
	protected function _initAcl(){
		
		$acl = new Zend_Acl();
		
		$acl->addResource(new Zend_Acl_Resource('administrateur'));
		$acl->addResource(new Zend_Acl_Resource('directionstrategique'));
		$acl->addResource(new Zend_Acl_Resource('drh'));
		$acl->addResource(new Zend_Acl_Resource('ajax'));
		$acl->addResource(new Zend_Acl_Resource('error'));
		$acl->addResource(new Zend_Acl_Resource('exploitation'));
		$acl->addResource(new Zend_Acl_Resource('index'));
		$acl->addResource(new Zend_Acl_Resource('maintenance'));
		$acl->addResource(new Zend_Acl_Resource('planning'));
		
		$acl->addRole(new Zend_Acl_Role('invite'));
		$acl->addRole(new Zend_Acl_Role('drh'), 'invite');
		$acl->addRole(new Zend_Acl_Role('directionstrategique'), 'invite');
		$acl->addRole(new Zend_Acl_Role('maintenance'), 'invite');
		$acl->addRole(new Zend_Acl_Role('pilote'), 'invite');
		$acl->addRole(new Zend_Acl_Role('planning'), 'invite');
		$acl->addRole(new Zend_Acl_Role('exploitation'), 'invite');
		$acl->addRole(new Zend_Acl_Role('administrateur'), array('drh', 'directionstrategique', 'maintenance', 'pilote', 'planning', 'exploitation'));
		
		$acl->deny(null, null);
		
		$acl->allow('invite', 'index');
		$acl->allow('invite', 'error');
		$acl->allow('invite', 'ajax');
		
		$acl->allow('directionstrategique', 'directionstrategique');
		$acl->allow('drh', 'drh');
		$acl->allow('maintenance', 'maintenance');
		$acl->allow('planning', 'planning');
		$acl->allow('exploitation', 'exploitation');
		
		$acl->allow('administrateur', 'administrateur');
		
		Zend_Registry::set('acl', $acl);
		
		$session = new Zend_Session_Namespace('role');
		
		$auth = Zend_Auth::getInstance();
			
		if($auth->hasIdentity()){
		
			$identity = $auth->getIdentity();
			$session->role = $identity->TUTI_alias;
			
		}
		else{
			$session->role = 'invite';
		}
		
		
		// var_dump($session->role);exit;
	}
	
} // Bootstrap

