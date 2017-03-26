{include file='_intro.tpl'}

{if empty($duel)}
	Nothing
{else}
<pre>
duel#{$duel.id} 
init_at: {$duel.init_at}
player1: {$player1.login} (#{$player1.id})
player2: {$player2.login} (#{$player2.id})
</pre>	

<textarea class="logdata">{$logdata}</textarea>

{/if}

<form id="knblsform" method="post" action="/knb/">

	<fieldset class="knbls">
	{foreach from=$spells item='v'}
	<button class="bttn bttn-{$v}" name="spell" value="{$v}">{$v}</button>
	{/foreach}
	</fieldset>

</form>
{include file='_outro.tpl'}