{include file='_intro.tpl'}

<pre>
message: {$message|default:'none'}
session: {$smarty.session|@print_r:true}
result: {if $result}{$result|@print_r:true}{else}false{/if}
</pre>

{if empty($User)}
<form class="wrpr" action="./" name="login" method="post">
	<h3>Login</h3>
	<input type="hidden" name="action" value="login" />
	<fieldset>
		<label for="login">Login</label>
		<input type="text" name="login" />
	</fieldset>
	<fieldset>
		<label for="password">Password</label>
		<input type="text" name="password" />
	</fieldset>
	<fieldset><input type="submit" /></fieldset>
</form>
{else}
<form class="wrpr" action="./" name="logout" method="post">
	<h3>Exit</h3>
	<input type="hidden" name="action" value="logout" />
	<fieldset>
		<label for="name">Name</label>
		<input type="text" name="name" value="{$User.name}" readonly />
	</fieldset>
	<fieldset>
		<label for="ssid">SSID</label>
		<input type="text" name="ssid" value="{$User.ssid}" readonly />
	</fieldset>
	<fieldset><input type="submit" value="Logout" /></fieldset>
</form>
{/if}

<a href="./">reload</a>

{*
<div class="wrpr oauth-apps">
{assign var='appid_yandex' value='0f1068c928064417a02612612099cd0f'}
<a onclick="oauth.popup(this,event)" href="https://oauth.yandex.ru/authorize?response_type=token&amp;display=popup&amp;client_id={$appid_yandex}">Yandex</a>
</div>
*}
{include file='_outro.tpl'}