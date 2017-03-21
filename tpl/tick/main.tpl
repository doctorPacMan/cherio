{include file='_intro.tpl'}

<div class="gameboard">

{if !empty($User)}
	<form class="ticktack" method="get" action="./">
	{strip}
	{foreach from=array('A','B','C') item='tx'}
	{foreach from=array('1','2','3') item='ty'}
		<button name="bttn" value="{$tx}{$ty}">
			{$tx}{$ty}
		</button>
	{/foreach}
	{/foreach}
	{/strip}
	</form>
{/if}

</div>

{include file='_outro.tpl'}