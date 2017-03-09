{include file='account/_intro.tpl'}

<h1>Account{if !empty($action)} : {$action}{/if}</h1>

{if !empty($errors)}
<ul class="errlist">{foreach from=$errors item='e'}<li>{$e}</li>{/foreach}</ul>
{/if}

{if !empty($User)}
	<pre class="wrpr">{$User.data}</pre>
	{include file='account/f_logout.tpl'}
{else}
	{include file='account/f_login.tpl'}
	{include file='account/f_register.tpl'}
{/if}

{include file='account/_outro.tpl'}