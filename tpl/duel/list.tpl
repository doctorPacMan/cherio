{include file='_intro.tpl'}

<h1>Duel list</h1>

<ul class="duelslist">
{foreach from=$duels item='v'}
<li>
	#{$v.id} {$v.player1} vs {$v.player2}
	<a class="btn" href="./?list&id={$v.id}">reset</a>
	<a class="btn" href="./?list&id={$v.id}">delete</a>
</li>
{/foreach}
</ul>

{include file='_outro.tpl'}