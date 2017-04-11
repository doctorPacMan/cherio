{include file='_intro.tpl'}

{if !empty($duel_data)}<pre class="dueldata">{$duel_data|@print_r:true}</pre>{/if}

{if !empty($duel_echo)}<textarea class="duelecho">{$duel_echo|@print_r:true}</textarea>{/if}
{if !empty($duel_text)}<textarea class="dueltext">{$duel_text|@print_r:true}</textarea>{/if}

<a class="btn">Restart</a>

<div class="gameboard">
	
	<big>Round 0</big>
	
	{strip}
	<div class="dueler{if $player1.id==$User.id} dueler-you{/if}" id="player1">
		<p>Player 1: {$player1.login} <b>100%</b></p>
		<form action="./" method="get">
			<input type="hidden" name="p" value="{$player1.id}" />
			{foreach from=$player1.spells item='s'}
			<button name="spell" value="{$s.id}" class="spl spl-{$s.id}" title="{$s.name}"></button>
			{/foreach}
		</form>
	</div>
	{/strip}

	{strip}
	<div class="dueler{if $player2.id==$User.id} dueler-you{/if}" id="player2">
		<p>Player 2: {$player2.login} <b>100%</b></p>
		<form action="./" method="get">
			<input type="hidden" name="p" value="{$player2.id}" />
			{foreach from=$player2.spells item='s'}
			<button name="spell" value="{$s.id}" class="spl spl-{$s.id}" title="{$s.name}"></button>
			{/foreach}
		</form>
	</div>
	{/strip}

</div>

{include file='_outro.tpl'}