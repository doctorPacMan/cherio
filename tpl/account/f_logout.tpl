<form class="wrpr" action="./" name="login" method="post">
	<h3>Logout user &laquo;{$User.name}&raquo; <small>{$User.ssid|default:'ssid'}</small></h3>
	<input type="hidden" name="action" value="logout" />
	<fieldset><input type="submit" value="Logout" /></fieldset>
	{* ({$smarty.const.SSID}) *}
</form>