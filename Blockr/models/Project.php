<?php namespace Blockr\Models;

class Project extends \Blockr\Models\BlockrModel {
	
	protected static $_props = array("_id", "name", "slug", "settings", "client");
	protected $_collection = "projects";

	protected $_name;//protected so that he parent class can reach
	protected $_client;
	protected $_slug;

	public function __construct($config=array()) {
		parent::__construct($config);
		if (isset($config['client'])) $this->client($config['client']);
		$this->_identifierField = "slug";
	}

	public function name($name=null) {
		if (!empty($name)) $this->_name = $name;
		return $this->_name;
	}

	public function client($client=null) {
		if (!empty($client)) {
			if (!is_a($client, "\Blockr\Models\Client")) {
				$client = new Client(array("slug"=>$client));
				$this->_client->load();
			}
			$this->_client = $client;
		}
		return $this->_client;
	}

	public function slug($slug=null) {
		if (!empty($slug)) {
			$s = new \Slug\Slugifier();
			$this->_slug = $s->slugify($slug);
		}
		return $this->_slug;
	}

	public function load($query = array()) {
		parent::load($query);
		if (!empty($this->_client)) {
			$this->_client = new \Blockr\Models\Client(array("slug"=>$this->_client['slug']));
			$this->_client->load();
		}
		return $this;
	}

	public function save() {
		$this->slug($this->_name);
		if (isset($this->_settings['active'])) $this->_settings['active'] = (boolean) $this->_settings['active'];
		//add the project to the client's projects
		$this->client()->insertProject(array("_id"=>$this->_id->__toString(), "name"=>$this->_name));
		if (!empty($this->_client)) {//summarise the client data
			$this->_client = array("_id"=>$this->_client->id(), "name"=>$this->_client->name(), "slug"=>$this->_client->slug());
		}
		parent::save();
		return $this;
	}

}