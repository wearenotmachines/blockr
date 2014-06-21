<form method="post" action="/clients/save">
<input type="text" name="client[name]" placeholder="client name" value="<?= $client->name(); ?>" />
<? if ($client->id()) { ?>
<input type="hidden" name="client[_id]" value="<?= $client->id(); ?>" />
<? } ?>
<label for="active"><input type="checkbox" name="client[settings][active]" id="active" value="true" checked /></label>
<input type="submit" value="Hit me up guy">
</form>