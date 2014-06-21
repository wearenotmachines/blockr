<?php namespace Blockr;

class MongoConn {
	
	private $_db;
	private $_collection = null;


	private static $_instance = null;


	private function __construct() {
		$client = new \MongoClient();
		$this->_db = $client->Blockr;
	}

	public static function getInstance() {
		if (empty(self::$_instance)) {
			self::$_instance = new MongoConn();
		}
		return self::$_instance;

	}

	public function collection($collectionName = null) {
		if (!empty($collectionName)) {
			$this->_collection = new \MongoCollection($this->_db, $collectionName);
		}
		return $this->_collection;
	}


}