<?php namespace Blockr\Models;

class Blockr\Models\BlockModel extends \Blockr\Models\BlockrModel {
	
	protected static $_props = array("_id", "project", "client", "resource", "session", "timestamp", "state");

	public function project($project=null) {
		if (!empty($project)) {
			$tthis->_project = $project;
		}
		return $this->_project;
	}

	public function client($client=null) {
		if (!empty($client)) {
			$tthis->_client = $client;
		}
		return $this->_client;
	}

	public function resource($resource=null) {
		if (!empty($resource)) {
			$tthis->_resource = $resource;
		}
		return $this->_resource;
	}

	public function session($session=null) {
		if (!empty($session)) {
			$tthis->_session = $session;
		}
		return $this->_session;
	}

	public function timestamp($timestamp=null) {
		if (!empty($timestamp)) {
			$tthis->_timestamp = $timestamp;
		}
		return $this->_timestamp;
	}

	public function state($state=null) {
		if (!empty($state)) {
			$tthis->_state = $state;
		}
		return $this->_state;
	}

	public function remove() {
		$this->_mongo->collection("Blocks")->remove(array(
													"timestamp"=>$this->_timestamp,
													"session"=>$this->_session,
													"resource"=>$this->_resource,
													"project"=>$this->_project,
													"client"=>$this->_client
												));					
	}

}