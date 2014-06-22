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

	public function lookup($startsWith=null) {
		if (empty($startsWith)) {
			$clients = \Blockr\Models\Client::find("clients", array(), 10, array("_id"=>-1));
		} else {
			$regex = new \MongoRegex("/".$startsWith."/i");
			$clients = \Blockr\Models\Client::find("clients", array('name'=>array('$regex'=>$regex)), 10);
		}
		$matches = array();
		foreach ($clients AS $c) {
			$matches[] = $c;
		}
		return json_encode($matches);
	}
	
}