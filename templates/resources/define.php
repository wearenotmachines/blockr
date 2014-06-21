<form method="post" action="/resource/save">
	<label>Name</label>
	<input type="text" name="resource[name]" placeholder="your name please" />
	<label>Email</label>
	<input type="email" name="resource[email]" placeholder="your email address please" required />
	<label for="active">Active <input id="active" type="checkbox" name="resource[settings][active]" value="1" checked /></label>
	<input type="submit" value="Do it" />
</form>