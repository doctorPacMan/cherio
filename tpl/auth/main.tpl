{include file='_intro.tpl'}

{if !empty($auth_error)}
	<p class="reglog-eror">{$auth_error}</p>
{/if}

{if !empty($User)}
	<pre>{$User|@print_r:true}</pre>
{else}
	
	<div class="reglog-form">
	<form action="./" name="login" method="post">
		<input type="hidden" name="action" value="login" />
		<input name="username" value="{$smarty.get.username|default:''}" type="text" placeholder="Логин" />
		<input name="userpass" value="{$smarty.get.userpass|default:''}" type="password" placeholder="Пароль" />
		<input type="submit" value="Вход" />
	</form>
	<form action="./" name="regah" method="post">
		<input type="hidden" name="action" value="regah" />
		<input name="username" value="{$smarty.post.username|default:''}" type="text" placeholder="Придумай логин" />
		<input name="userpass" value="{$smarty.post.userpass|default:''}" type="password" placeholder="Придумай пароль" />
		<input type="submit" value="Регистрация" />
	</form>
	</div>

{/if}

{if !empty($users)}
	<h1>Все пользователи:</h1>
	<ul class="reglog-list">
	{foreach from=$users item='v'}
	<li><a href="./?username={$v.login|escape:'url'}&amp;userpass={$v.pass|escape:'url'}">[{$v.id}] {$v.login} @{$v.pass}</a></li>
	{/foreach}
	</ul>
{/if}

{include file='_outro.tpl'}