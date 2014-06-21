<?php namespace Blockr\Controllers;

class ResourceController extends \Blockr\Controllers\BaseController {
	
	public function __construct($app) {
		parent::__construct($app);
		$this->_data['controller'] = "ResourceController";
	}

	public function define() {
		$this->_data['value'] = "test";
		$this->_app->render("resources/define.php",$this->_data);

	}


}