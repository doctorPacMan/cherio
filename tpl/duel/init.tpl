{include file='_intro.tpl'}

<h3>Duel init</h3>

{if isset($smarty.get.success)}<div class="message-success">SUCCESS</div>
{elseif $create_result===false}<div class="message-failure">FAILURE</div>
{/if}

{if !empty($create_result)}<pre>{$create_result|@print_r:true}</pre>{/if}
{if !empty($create_errors)}<pre>{$create_errors|@print_r:true}</pre>{/if}

<br><br>
<table>
<tr>
	<th width="20">id</th>
	<th>login</th>
	<th width="90" align="center"></th>
</tr>
{foreach from=$users item='v'}
<tr{if $User.id==$v.id} class="hlgt"{/if}>
	<td>{$v.id}</td>
	<td>{$v.login}</td>
	<td>
		{if $v.duel}duel #{$v.duel}
		{else}<a class="btn" href="./?id={$v.id}">к барьеру!</a>
		{/if}
	</td>
</tr>
{/foreach}
</table>

{include file='_outro.tpl'}