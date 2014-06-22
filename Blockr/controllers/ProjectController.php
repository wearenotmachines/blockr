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
		$p = new \Blockr\Models\Project($projectData);
		echo "<pre>"; print_r($p->save()); echo "</pre>";
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
			$matches[$p['_id']->{'$id'}] = $p['name'];
		}
		return json_encode($matches);
	}
}