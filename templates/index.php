<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Blockr - Kick-ass project management</title>
	<link rel="stylesheet" href="styles.css" type="text/css" />
</head>
<body>

<div id="header">
	<div class="manager" id="projectManager">
		<a class="button project" href="/projects/choose">project</a>
		<div class="selection" id="projectSelection">
			<div id="newProject">
				<input type="text" name="project[name]" placeholder="start a new project" />
				<label for="projectActive">live</label><input type="checkbox" name="project[active]" checked />
				<input type="text" data-lookup-href="/clients/lookup" data-suggestable-role="label" class="suggestable" placeholder="find a client" />
				<input type="hidden" name="project[client]" data-suggestable-role="value" class="suggestable" />
				<button data-save-href="/projects/save">Do it</button>
			</div>
			<ul id="projectsMatching">
				<li class="placeholder">wait for it....</li>
			</ul>
		</div>
	</div>
	<div class="manager" id="clientManager">
		<a class="button client" href="/clients/choose">client</a>
		<div class="selection" id="clientSelection">
			<div id="newClient">
				<input type="text" name="client[name]" placeholder="add a new client" />
				<label for="clientActive">live</label>
				<input type="checkbox" name="client[active]" id="clientActive" checked />
				<button data-save-href="/clients/save">Make it so</button>
			</div>
			<ul id="clientsMatching">
				<li class="placeholder">hang about.....</li>
			</ul>
		</div>
	</div>
	<div class="manager" id="resourceManager">
		<a class="button resource" href="/resources/choose">resource</a>
		<div class="selection" id="resourceSelecttion">
			<div id="newResource">
				<input type="text" name="resource[name]" placeholder="name this" />
				<input type="email" name="resource[email]" placeholder="email address" />
				<label for="resourceActive">live</label>
				<input type="checkbox" name="resource[active]" id="resourceActive" value="1" checked />
				<button data-save-href="/resources/save">Hit it</button>
			</div>
			<ul id="resourcesMatching">
				<li class="placeholder">working on it....</li>
			</ul>
		</div>
	</div>
</div>
<div id="blockr">
</div>
<div id="footer">
</div>	
</body>
	</html>