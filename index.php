<?php
	ini_set("display_errors", "on");
	error_reporting(E_ALL);
	require "vendor/autoload.php";
	$app = new \Slim\Slim();

	$app->map("/", function() use ($app) {
		$Controller = new \Blockr\Controllers\BlockrController($app);
		$Controller->index();
	})->via('GET', 'POST');

	$app->get("/info", function() {
		phpinfo();
	});

	$app->get("/mongo", function() {
		$resources = \Blockr\Models\Resource::find("resources", array("settings"=>array('$ne'=>array("active"=>"1"))));
		foreach ($resources AS $r) {
			echo "<pre>"; print_r($r); echo "</pre>";
		}
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
	$app->get("/resources/new", function() use ($app) {

		$ResourceController = new \Blockr\Controllers\ResourceController($app);
		$ResourceController->define();

	});
	$app->post("/resources/save", function() use ($app) {
		$r = new \Blockr\Models\Resource($app->request->params('resource'));
		echo $r->checkIdentifier();
		echo "<br />";
		 echo $r->toJSON();
		 echo "<pre>"; print_r($r->save()); echo "</pre>";
	});

	$app->get("/projects/new", function() use ($app) {
		$ProjectController = new \Blockr\Controllers\ProjectController($app);
		$ProjectController->define();
	});
	
	$app->map("/projects/lookup", function() use ($app) {
		$ProjectController = new \Blockr\Controllers\ProjectController($app);
		echo $ProjectController->lookup($app->request->params('search'));
	})->via('GET', 'POST');

	$app->get("/projects/:slug", function($slug) use ($app) {
		$p = new \Blockr\Models\Project(array("slug"=>$slug));
		$p->load();
		$ProjectController = new \Blockr\Controllers\ProjectController($app);
		$ProjectController->define($p);
	});


	$app->post("/projects/save", function() use ($app) {
		$ProjectController = new \Blockr\Controllers\ProjectController($app);
		$ProjectController->save($app->request->params('project'));
	});

	$app->get("/clients/new", function() use($app) {
		$ClientController = new \Blockr\Controllers\ClientController($app);
		$ClientController->define();
	});

	$app->post("/clients/save", function() use ($app) {
		$ClientController = new \Blockr\Controllers\ClientController($app);
		$ClientController->save($app->request->params('client'));
	});

	$app->map("/clients/lookup", function() use ($app) {
		$ClientController = new \Blockr\Controllers\ClientController($app);
		echo $ClientController->lookup($app->request->params('search'));
	})->via('GET', 'POST');

	$app->get("/clients/:slug", function($slug) use ($app) {
		$ClientController = new \Blockr\Controllers\ClientController($app);
		$ClientController->define(new \Blockr\Models\Client(array("slug"=>$slug)));
	});

	$app->run();