<table class="dbtable">
{foreach from=$data name='dbt' item='li'}

	{if $smarty.foreach.dbt.first}
	<tr>
		{foreach from=$li name='dbl' item='v' key='k'}
		<th>{$k}</th>
		{/foreach}
	</tr>
	{/if}
	
	<tr>
		{foreach from=$li name='dbl' item='v' key='k'}
		<td>{$v}</td>
		{/foreach}
	</tr>

{/foreach}
</table>