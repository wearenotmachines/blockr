<?php namespace Blockr\Controllers;

class BlockrController extends \Blockr\Controllers\BaseController {

	public function __construct($app) {
		parent::__construct($app);
		$this->_data['controller'] = "BlockrController";
	}

	public function index() {
		$this->_app->render("index.php", $this->_data);
	}
	
}