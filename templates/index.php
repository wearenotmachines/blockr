<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Blockr - Kick-ass project management</title>
	<link rel="stylesheet" href="styles.css" type="text/css" />
	<script src="jquery.min.js"></script>
	<script src="blockr.js"></script>
</head>
<body>

<div id="header">
	<div class="manager" id="projectManager" data-type="projects">
		<a class="button project" href="/projects/lookup">project</a>
		<div class="selection" id="projectSelection">
			<input type="search" class="search" data-lookup-href="/projects/lookup" name="projectSearch" placeholder="find a project" />
			<a href="/projects/new" class="new">+</a>
			<div id="newProject" class="objectDefinition">
				<input type="text" name="project[name]" placeholder="start a new project" />
				<label for="projectActive">live</label><input type="checkbox" name="project[active]" checked />
				<input type="text" name="project[client]" data-lookup-href="/clients/lookup" data-suggestable-role="label" class="suggestable" placeholder="find a client" />
				<button data-save-href="/projects/save">Do it</button>
			</div>
			<ul class="suggestionList" id="projectsMatching" data-type="projects">
				<li class="placeholder">wait for it....</li>
			</ul>
		</div>
	</div>
	<div class="manager" id="clientManager" data-type="clients">
		<a class="button client" href="/clients/lookup">client</a>
		<div class="selection" id="clientSelection">
			<input type="search" class="search" data-lookup-href="/clients/lookup" name="clientSearch" placeholder="find a client" />
			<a href="/clients/new" class="new">+</a>
			<div id="newClient" class="objectDefinition">
				<input type="text" name="client[name]" placeholder="add a new client" />
				<label for="clientActive">live</label>
				<input type="checkbox" name="client[active]" id="clientActive" checked />
				<button data-save-href="/clients/save">Make it so</button>
			</div>
			<ul class="suggestionList" id="clientsMatching" data-type="clients">
				<li class="placeholder">hang about.....</li>
			</ul>
		</div>
	</div>
	<div class="manager" id="resourceManager" data-type="resources">
		<a class="button resource" href="/resources/lookup">resource</a>
		<div class="selection" id="resourceSelecttion">
			<input type="search" class="search" data-lookup-href="/resources/lookup" name="resourceSearch" placeholder="find a resource" />
			<a href="/resources/new" class="new">+</a>
			<div id="newResource" class="objectDefinition">
				<input type="text" name="resource[name]" placeholder="name this" />
				<input type="email" name="resource[email]" placeholder="email address" />
				<label for="resourceActive">live</label>
				<input type="checkbox" name="resource[settings][active]" id="resourceActive" value="1" checked />
				<button data-save-href="/resources/save">Hit it</button>
			</div>
			<ul class="suggestionList" id="resourcesMatching" data-type="resources">
				<li class="placeholder">working on it....</li>
			</ul>
		</div>
	</div>
</div>
<div id="blockr">
<? for ($i=0; $i<5; $i++) { ?>
	<div class="column ofFive">
		<h1>COLUMN</h1>
		<? 
		$blockStart = null;
		foreach ($blocks AS $block) { 
				if ($block['session']=="am") { ?>
				<div data-timestamp="<?= $block['timestamp']; ?>" class="day">
			<? } ?>
					<div class="session <?= $block['session']; ?>" data-state="unassigned" data-session="<?= $block['session']; ?>" data-timestamp="<?= $block['timestamp']; ?>">
						<? if ($block['session']=="am") { ?><h5><?= $block['day']." - ".$block['date']; ?></h5><? } ?>
						<h6><?= $block['session']; ?></h6>
						<span class="label"></span>
					</div>
			<? if ($block['session']=="pm") { ?>
				</div>
			<? } ?>				
		<? } ?>
	</div>
<? } ?>
<p class="clear"></p>
</div>
<div id="footer">
</div>	
</body>
	</html>