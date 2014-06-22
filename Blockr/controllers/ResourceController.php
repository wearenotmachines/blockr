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

	public function save($resourceData) {
		$resource = new \Blockr\Models\Resource($resourceData);
		try {
			echo $resource->save()->toJSON();
		} catch(Exception $e) {
			$this->_app->response->setStatus(500);
			$this->_app->response->write("Error saving resource");
		}
	}

	public function lookup($startsWith=null) {
		if (empty($startsWith)) {
			$resources = \Blockr\Models\Resource::find("resources", array(), 10, array("_id"=>-1));
		} else {
			$regex = new \MongoRegex("/".$startsWith."/i");
			$resources = \Blockr\Models\Resource::find("resources", array('$or'=>array('name'=>array('$regex'=>$regex)), array('email'=>array('$regex'=>new \MongoRegex("/".$startsWith."/i")))), 10);
		}
		$matches = array();
		foreach ($resources AS $r) {
			$matches[] = $r;
		}
		return json_encode($matches);
	}


}