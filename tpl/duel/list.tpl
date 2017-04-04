{include file='_intro.tpl'}

{if !empty($rzlt)}<pre>{$rzlt|@print_r:true}</pre><br />{/if}

<h1>Duel list</h1>

<table class="duelslist">
<thead>
<tr>
	<th width="30">id</th>
	<th width="60">player1</th>
	<th width="60">player2</th>
	<th>actions</th>
</tr>
</thead>
<tbody>
{foreach from=$duels item='v'}
<tr>
	<td>{$v.id}</td>
	<td>{$v.player1}</td>
	<td>{$v.player2}</td>
	<td>
		<a class="btn" href="./?reset={$v.id}">reset</a>
		<a class="btn" href="./?delete={$v.id}">delete</a>
	</td>
</tr>
{/foreach}
</tbody>
</table>

{include file='_outro.tpl'}