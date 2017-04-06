{include file='_intro.tpl'}

<h3>Duel init</h3>

{if isset($smarty.get.success)}SUCCESS
{elseif $create_result===false}FAILURE
{/if}

{if !empty($create_result)}<pre>{$create_result|@print_r:true}</pre>{/if}
{if !empty($create_errors)}<pre>{$create_errors|@print_r:true}</pre>{/if}

<br><br>
<table>
<tr>
	<th width="20">id</th>
	<th>login</th>
	<th width="90"></th>
</tr>
{foreach from=$users item='v'}
<tr>
	<td>{$v.id}</td>
	<td>{$v.login}</td>
	<td><a class="btn"{if !$v.duel} href="./?id={$v.id}"{/if}>к барьеру!</a></td>
</tr>
{/foreach}
</table>

{include file='_outro.tpl'}