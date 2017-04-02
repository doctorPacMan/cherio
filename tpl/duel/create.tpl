<h1>Duel create</h1>
<ul class="userslist">
{foreach from=$users item='v'}
<li>
	{$v.id}
	<b>{$v.login}</b>
	<a class="btn"{if $v.id!=$User.id} href="./?create&uid={$v.id}"{/if}>fight</a>
</li>
{/foreach}
</ul>