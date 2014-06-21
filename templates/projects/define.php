<form method="post" action="/project/save">
	<input type="text" name="project[name]" placeholder="project name" value="<?= $project->name(); ?>" />
	<input type="submit" value="Make it so" />
</form>