<?php namespace Blockr\Controllers;

class BlockrController extends \Blockr\Controllers\BaseController {

	public function __construct($app) {
		parent::__construct($app);
		$this->_data['controller'] = "BlockrController";
	}

	public function index() {
		$this->_data['blocks'] = \Blockr\Models\BlockrModel::makeBlocks(time(), "+30 days", null);
		$this->_app->render("index.php", $this->_data);
	}
	
}