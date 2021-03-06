<?php namespace Blockr\Models;

class BlockrModel {
	
	protected $_mongo;
	protected static $_props = array();
	protected $_collection;//the mongo collection these objects map to

	protected $_id;
	protected $_settings = array();
	protected $_identifierField = "_id";

	public static function find($type, $where=array(), $howMany=null, $sort=null) {
		$mongo = \Blockr\MongoConn::getInstance();
		$res = $mongo->collection($type)->find($where);
		if ($sort) $res->sort($sort);
		if ($howMany) $res->limit($howMany);
		return $res;
	}

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
					$k = trim($k, "_");
					//wrap _ids as mongo ids
					if ($k=="id" && !is_a($v, "MongoId")) {

						$v = new \MongoId($v);	
					}
					$this->{"_".$k} = $v;
				}			
			}
		}
		if (empty($this->_id)) $this->_id = new \MongoId();
	}

	public function _id($id=null) {
		return $this->id($id);
	}

	public function id($id=null) {
		if (!empty($id)) $this->_id = !is_a($id, "MongoId")  ? $id : new MongoId($id);
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
			return null;
		}
	}

	public function save() {
		return $this->_mongo->collection($this->_collection)->findAndModify(array("_id"=>$this->_id), $this->getProps(), null, array("new"=>true, "upsert"=>true));
	}

	public function getProps() {
		$props = array();
		foreach (static::$_props AS $prop) {
			if ($this->{$prop}()===null) continue;
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

	public static function makeBlocks($starting, $duration="+30 days", $loadDataFor=null) {
		$bounds = array();
		$bounds["starts"] = is_int($starting) ? strtotime("midnight", $starting) : strtotime($starting);
		$bounds["ends"] = is_int($duration) ? $duration : strtotime($duration, $bounds['starts']);
		$t = 60*60*24; //twenty four hours of seconds
		$p = 60*60*12; //twelve hours of seconds
		$blocks = array();
		for ($i=$bounds['starts']; $i<$bounds["ends"]; $i+=$t) {
			$cAM = \Carbon\Carbon::createFromTimestamp($i);
			$cPM = \Carbon\Carbon::createFromTimestamp($i+$p);
			$blocks[$i] = array(
					"isWeekend"=>(int)$cAM->isWeekend(),
					"timestamp"=>$i,
					"date"=>$cAM->format("d/m/Y"),
					"day"=>$cAM->format("l"),
					"session"=>$cAM->format("a")
					);
			$blocks[$i+$p] = array(
						"isWeekend"=>(int)$cPM->isWeekend(),						
						"timestamp"=>$i+$p,
						"date"=>$cPM->format("d/m/Y"),
						"day"=>$cPM->format("l"),
						"session"=>$cPM->format("a")						
					);
		}	
		return $blocks;
	}

}