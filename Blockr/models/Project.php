<?php namespace Blockr\Models;

class Project extends \Blockr\Models\BlockrModel {
	
	protected static $_props = array("id", "name", "slug", "settings");
	protected $_collection = "projects";

	protected $_name;//protected so that he parent class can reach
	protected $_client;
	protected $_slug;

	public function __construct($config=array()) {
		parent::__construct($config);
		$this->_identifierField = "slug";
	}

	public function setName() {
		$this->_name = $name;
	}

	public function name($name=null) {
		if (!empty($name)) $this->_name = $name;
		return $this->_name;
	}

	public function setClient($client) {
		$this->_client = $client;
	}

	public function client() {
		return $this->_client;
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
		return parent::save();
	}

}