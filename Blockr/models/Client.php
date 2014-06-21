<?php namespace Blockr\Models;

class Client extends BlockrModel {
	

	protected static $_props = array("_id", "name", "slug", "settings");

	protected $_collection = "clients";

	protected $_name;
	protected $_slug;
	public $projects = array();

	public function __construct($config=array()) {
		parent::__construct($config);
		$this->_identifierField = "slug";
	}

	public function name($name=null) {
		if (!empty($name)) $this->_name = $name;
		return $this->_name;
	}

	public function slug($slug=null) {
		if (!empty($slug)) {
			$s = new \Slug\Slugifier();
			$this->_slug = $s->slugify($slug);
		}
		return $this->_slug;
	}

	public function save() {
		$this->slug($this->_name);
		if (isset($this->_settings['active'])) $this->_settings['active'] = (boolean) $this->_settings['active'];
		$result = parent::save();
		if (!empty($this->projects)) {
			$this->updateProjects();
		}
	}

	public function load($getProjects=false) {
		parent::load();
		if ($getProjects) {
			$this->projects = self::find("projects");
		}
	}

	public function updateProjects() {
		$projectData = array();
		foreach ($this->projects AS $p) {
			$projectData[] = array($p->id()=>$p->name());
		}
		$this->_mongo->collection("clients")->update(array("_id"=>$this->id()), array('$set'=>array("projects"=>$projectData)));
	}

	public function insertProject($projectSummary) {
		return $this->_mongo->collection("clients")->update(array("_id"=>$this->id()), array('$push'=>array("projects"=>$projectSummary)));
	} 


}