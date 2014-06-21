<?php namespace Blockr\Models;

class BlockrModel {
	
	protected $_mongo;
	protected static $_props = array();
	protected $_collection;//the mongo collection these objects map to

	protected $_id;
	protected $_settings = array();
	protected $_identifierField = "id";


	public function __construct($config = array()) {
		$this->_mongo = \Blockr\MongoConn::getInstance();
		$this->_init($config);
	}

	/**
	* A helper function that initializes properties on a child constructor - must be implemented in the child after calling the parent constructor as the props array will not exist before this
	*/
	protected function _init($config) {
		if (!empty($config)) {
			foreach($config AS $k=>$v) {
				if (in_array($k, static::$_props)) {
					$this->{"_".$k} = $v;
				}
			}
		}

	}

	public function setID($id) {
		$this->_id = $id;
	}

	public function id() {
		return $this->_id;
	}

	public function mongo() {
		return $this->_mongo;
	}

	public function load($query = array()) {
		if (empty($query)) {
			$res = $this->_mongo->collection($this->_collection)->findOne(array($this->_identifierField=>$this->{$this->_identifierField}()));
		} else {
			$res = $this->_mongo->collection($this->_collection)->findOne($query);
		}
		if (!empty($res)) {
			$this->_init($res);
		} else {
			throw new \Exception("Could not find anything for ".http_build_query($query));
		}
	}

	public function save() {
		return $this->_mongo->collection($this->_collection)->findAndModify(array($this->_identifierField=>$this->{$this->_identifierField}()), $this->getProps(), null, array("new"=>true, "upsert"=>true));
	}

	public function getProps() {
		$props = array();
		foreach (static::$_props AS $prop) {
			if ($prop=="id" || $this->{$prop}()===null) continue;
			$props[$prop] = is_array($prop) ? json_encode($this->{$prop}()) : $this->{$prop}();
		}
		return $props;
	}

	public function toJSON() {
		return json_encode($this->getProps(), JSON_PRETTY_PRINT);
	}

	public function checkIdentifier() {
		return $this->_identifierField." :: ".$this->{$this->_identifierField}();
	}

	public function settings($settings=null) {
		if ($settings!==null) $this->_settings = $settings;
		return $this->_settings;
	}

	public function getSettings($asJSON = true) {
		return $asJSON ? json_encode($this->_settings) : $this->_settings;
	}

	public function setting($key, $value=null) {
		if ($value===null) return $this->_settings[$key];
		$this->_settings[$key] = $value;
		return $this->_settings[$key];
	}

	public function getSetting($key) {
		return isset($this->_settings[$key]) ? $this->_settings[$key] : null;
	}

	public function replaceSettings($settingsArray) {
		$this->_settings = $settingsArray;
	}

}