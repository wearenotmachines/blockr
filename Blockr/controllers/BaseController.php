<?php namespace Blockr\Controllers;

class BaseController {
	
	protected $_app;
	protected $_data = array();

	public function __construct($app) {
		$this->_app = $app;
		$this->_data = array();
	}

	public function getApp() {
		return $this->_app;
	}

	public function test() {
		echo "<pre>"; print_r($this->getApp()); echo "</pre>";
	}
}