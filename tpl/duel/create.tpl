{include file='_intro.tpl'}

<h1>Duel create</h1>

<ul class="userslist">
{foreach from=$users item='v'}
<li>
	{$v.id}
	<b>{$v.login}</b>
	<a class="btn"{if $v.id!=$User.id} href="./?create&uid={$v.id}"{/if}>к барьеру!</a>
</li>
{/foreach}
</ul>

{include file='_outro.tpl'}