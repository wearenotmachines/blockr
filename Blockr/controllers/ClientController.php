<?php namespace Blockr\Controllers;

class ClientController extends BaseController {

	public function __construct($app) {
		parent::__construct($app);
		$this->_data['controller'] = "ClientController";
	}

	public function define(\Blockr\Models\Client $client=null) {
		if (empty($client)) {
			$client = new \Blockr\Models\Client();
		} else {
			$client->load();
		}
		$this->_data['client'] = $client;
		$this->_app->render("clients/define.php", $this->_data);
	}

	public function save($clientData) {
		$client = new \Blockr\Models\Client($clientData);
		echo "<pre>"; print_r($client->save()); echo "</pre>";
	}
	
}