{include file='_intro.tpl'}

{if !empty($duel_data)}<pre>{$duel_data|@print_r:true}</pre>{/if}

<a class="btn">Restart</a>

Player1:
Player2:

{*
<div class="gameboard">
	
	<big>Round {$round.num}</big>
	
	{strip}
	<div class="dueler{if $player1.id==$User.id} dueler-you{/if}" id="player1">
		<p>Player 1: {$player1.login} {$round.player1_hp}%</p>
		{foreach from=$player1.spells item='s'}
		<button value="{$s.id}" class="spl spl-{$s.id}" title="{$s.name}"></button>
		{/foreach}
	</div>
	{/strip}

	{strip}
	<div class="dueler{if $player2.id==$User.id} dueler-you{/if}" id="player2">
		<p>Player 2: {$player2.login} {$round.player2_hp}%</p>
		{foreach from=$player2.spells item='s'}
		<button value="{$s.id}" class="spl spl-{$s.id}" title="{$s.name}"></button>
		{/foreach}
	</div>
	{/strip}

</div>
*}
{include file='_outro.tpl'}