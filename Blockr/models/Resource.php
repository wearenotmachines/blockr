<?php namespace Blockr\Models;

class Resource extends \Blockr\Models\BlockrModel {
	
	
	protected $_name;//protected so that he parent class can reach
	protected $_email;
	protected $_avatar;

	protected static $_props = array("id", "name", "email", "avatar", "settings");
	protected $_collection = "resources";

	public function __construct($config=array()) {
		parent::__construct($config);
		$this->_identifierField = "email";
	}

	public function name($name=null) {
		if (!empty($name)) $this->_name = $name;
		return $this->_name;
	}

	public function email($email=null) {
		if (!empty($email)) $this->_email = $email;
		return $this->_email;
	}

	public function avatar($avatar = null) {
		if (!empty($avatar)) $this->_avatar = $avatar;
		return $this->_avatar;
	}

	public function greet() {
		return "Hi there, ".$this->_name;
	}
}

