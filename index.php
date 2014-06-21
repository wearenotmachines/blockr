<?php
	ini_set("display_errors", "on");
	error_reporting(E_ALL);
	require "vendor/autoload.php";
	$app = new \Slim\Slim();

	$app->get("/info", function() {
		phpinfo();
	});

	$app->get("/mongo", function() {
		$mongo = \Blockr\MongoConn::getInstance();
		$collection = $mongo->collection("resources");
		$res = $collection->findAndModify(array("email"=>"alex@wearenotmachines.com"), array('$set'=>array("email"=>"alex@wearenotmachines.com", "name" => "Alex")), null, array("new"=>true, "upsert"=>true));
		var_dump($res);
	});

	$app->get("/resource", function() use ($app) {
		// echo "Resource";
		$r = new \Blockr\Models\Resource();
		$r->setName("Alex");
		$r->setEmail("alex@wearenotmachines.com");
		$r->setting("likes","Cheese");
		$r->setting("is","Terrified");
		$res = $r->save();
		var_dump($res);
	});
	$app->get("/resource/new", function() use ($app) {

		$ResourceController = new \Blockr\Controllers\ResourceController($app);
		$ResourceController->define();

	});
	$app->post("/resource/save", function() use ($app) {
		$r = new \Blockr\Models\Resource($app->request->params('resource'));
		echo $r->checkIdentifier();
		echo "<br />";
		 echo $r->toJSON();
		 echo "<pre>"; print_r($r->save()); echo "</pre>";
	});

	$app->get("/project/new", function() use ($app) {
		$ProjectController = new \Blockr\Controllers\ProjectController($app);
		$ProjectController->define();
	});

	$app->get("/project/:slug", function($slug) use ($app) {
		$p = new \Blockr\Models\Project(array("slug"=>$slug));
		$p->load();
		$ProjectController = new \Blockr\Controllers\ProjectController($app);
		$ProjectController->define($p);
	});

	$app->post("/project/save", function() use ($app) {
		$p = new \Blockr\Models\Project($app->request->params('project'));
		echo "<pre>"; print_r($p->save()); echo "</pre>";
	});

	$app->run();