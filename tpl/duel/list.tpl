{include file='_intro.tpl'}

{if !empty($rzlt)}<pre>{$rzlt|@print_r:true}</pre><br />{/if}

{if isset($smarty.get.reset_success)}
<div class="message-success">Duel reset success</div>
{/if}

{if !empty($smarty.get.delete_success)}<div class="message-success">Duel deleted</div>
{elseif !isset($smarty.get.delete_failure)}
{elseif $smarty.get.file=='failure'}<div class="message-failure">Delete failure: fail</div>
{elseif $smarty.get.base=='failure'}<div class="message-failure">Delete failure: base</div>
{/if}

<h3>Duel list</h3>

<table class="duelslist">
<thead>
<tr>
	<th width="30">id</th>
	<th width="70">init_at</th>
	<th width="60">player1</th>
	<th width="60">player2</th>
	<th width="20">complete</th>
	<th>actions</th>
</tr>
</thead>
<tbody>
{foreach from=$duels item='v'}
<tr{if !empty($User) && $User.duel==$v.id} class="hlgt"{/if}>
	<td>{$v.id}</td>
	<td>{$v.init_at|date_format:'%D %T'}</td>
	<td>{$v.player2}</td>
	<td>{$v.player2}</td>
	<td>{$v.complete}</td>
	<td>
		<a class="btn" href="./?reset={$v.id}">reset</a>
		<a class="btn" href="./?delete={$v.id}">delete</a>
		<button class="btn" onclick="alert('{$v.combatlog|escape:'quotes'|strip}')">result</button>
	</td>
</tr>
{/foreach}
</tbody>
</table>

{include file='_outro.tpl'}