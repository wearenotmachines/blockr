<?php namespace Blockr\Controllers;

class ProjectController extends \Blockr\Controllers\BaseController {

	public function __construct($app) {
		parent::__construct($app);
		$this->_data['controller'] = "ProjectController";
	}

	public function define(\Blockr\Models\Project $project=null) {
		if (empty($project)) {
			$project = new \Blockr\Models\Project();
		} else {
			$project->load();
		}
		$this->_data['project'] = $project;
		$this->_app->render("projects/define.php",$this->_data);
	}	

	public function save($projectData=array()) {
		//lookup the client / create a new client
		$clientMatches = \Blockr\Models\Client::find("clients", array("name"=>$projectData['client']));
		if ($clientMatches->count()) {
			$clientMatches->next();
			$client = new \Blockr\Models\Client($clientMatches->current());
		} else {
			$client = new \Blockr\Models\Client(array("name"=>$projectData['client']));
			$client->setting("active", true);
			$client->save();
		}
		unset($projectData['client']);
		$p = new \Blockr\Models\Project($projectData);
		$p->client($client);
		try {
			echo $p->save()->toJSON();
		} catch (Exception $e) {
			$this->_app->response->setStatus(500);
			$this->_app->response->write("Project Create Failed");
		}
	}

	public function lookup($startsWith=null) {
		if (empty($startsWith)) {
			$projects = \Blockr\Models\Project::find("projects", array(), 10, array("_id"=>-1));
		} else {
			$regex = new \MongoRegex("/".$startsWith."/i");
			$projects = \Blockr\Models\Project::find("projects", array('name'=>array('$regex'=>$regex)), 10);
		}
		$matches = array();
		foreach ($projects AS $p) {
			$matches[] = $p;
		}
		return json_encode($matches);
	}
}