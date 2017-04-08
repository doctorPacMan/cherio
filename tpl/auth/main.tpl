{include file='_intro.tpl'}

{if !empty($auth_error)}
	<p class="reglog-eror">{$auth_error}</p>
{/if}

{if !empty($User)}
	<h3>{$User.login} <a href="./?logout">logout</a></h3>
	<pre>{$User|@print_r:true}</pre>
{else}
	
	<div class="reglog-form">
	<form action="./" name="login" method="post">
		<input type="hidden" name="action" value="login" />
		<input name="username" value="{$smarty.get.username|default:''}" type="text" placeholder="Логин" />
		<input name="userpass" value="{$smarty.get.userpass|default:''}" type="password" placeholder="Пароль" />
		<input class="btn" type="submit" value="Вход" />
	</form>
	<form action="./" name="regah" method="post">
		<input type="hidden" name="action" value="regah" />
		<input name="username" value="{$smarty.post.username|default:''}" type="text" placeholder="Придумай логин" />
		<input name="userpass" value="{$smarty.post.userpass|default:''}" type="password" placeholder="Придумай пароль" />
		<input class="btn" type="submit" value="Регистрация" />
	</form>
	</div>

{/if}

{if !empty($users) && empty($User)}
	<br><h3>Accounts:</h3>
	<table>
	<tr>
		<th width="30">id</th>
		<th>login</th>
		<th>duel</th>
		<th width="60"></th>
	</tr>
	{foreach from=$users item='v'}
	<tr>
		<td>{$v.id}</td>
		<td>{$v.login}</td>
		<td>{if !empty($v.duel)}{$v.duel}{/if}</td>
		<td><a class="btn" href="./?username={$v.login|escape:'url'}&amp;userpass={$v.pass|escape:'url'}">login</a></td>
	</tr>
	{/foreach}
	</table>
{/if}

{include file='_outro.tpl'}