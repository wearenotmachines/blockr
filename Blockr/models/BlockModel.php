<?php namespace Blockr\Models;
use \Blockr\Models;
class BlockModel extends BlockrModel {
	
	protected static $_props = array("_id", "project", "client", "resource", "session", "timestamp", "state");
	protected $_collection = "blocks";

	public function __construct($config = array()) {
		parent::__construct($config);
	}

	public function project($project=null) {
		if (!empty($project)) {
			$this->_project = $project;
		}
		return empty($this->_project) ? null : $this->_project;
	}

	public function client($client=null) {
		if (!empty($client)) {
			$this->_client = $client;
		}
		return empty($this->_client) ? null : $this->_client;
	}

	public function resource($resource=null) {
		if (!empty($resource)) {
			$this->_resource = $resource;
		}
		return empty($this->_resource) ? null : $this->_resource;
	}

	public function session($session=null) {
		if (!empty($session)) {
			$this->_session = $session;
		}
		return empty($this->_session) ? null : $this->_session;
	}

	public function timestamp($timestamp=null) {
		if (!empty($timestamp)) {
			$this->_timestamp = $timestamp;
		}
		return empty($this->_timestamp) ? null : $this->_timestamp;
	}

	public function state($state=null) {
		if (!empty($state)) {
			$this->_state = $state;
		}
		return empty($this->_state) ? null : $this->_state;
	}

	public function remove() {
		if ($this->_state=="unassigned") return true;
		$this->_mongo->collection("blocks")->remove(array("_id"=>$this->_id));					
	}

}