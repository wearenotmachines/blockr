<form method="post" action="/projects/save">
	<input type="text" name="project[name]" placeholder="project name" value="<?= $project->name(); ?>" />
	<label for="active">Active <input id="active" type="checkbox" name="project[settings][active]" value="1" checked /></label>
	<? if ($project->id()) { ?>
		<input type="hidden" name="project[_id]" value="<?= $project->id(); ?>" />
	<? } ?>
	<input type="text" name="project[client]" placeholder="who is the client" value="<?= $project->client() ? $project->client()->name() : ""; ?>" />
	<input type="submit" value="Make it so" />
</form>