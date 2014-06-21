<?php namespace Blockr\Controllers;

class ProjectController extends \Blockr\Controllers\BaseController {

	public function __construct($app) {
		parent::__construct($app);
		$this->_data['controller'] = "ProjectController";
	}

	public function define(\Blockr\Models\Project $project=null) {
		if (empty($project)) $project = new \Blockr\Models\Project();
		$this->_data['project'] = $project;
		$this->_app->render("projects/define.php",$this->_data);
	}	
}