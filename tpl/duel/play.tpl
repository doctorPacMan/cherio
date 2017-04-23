{include file='_intro.tpl'}
<script type="text/javascript" src="/js/play.js"></script>

{if !empty($duel_data)}<pre class="dueldata">{$duel_data|@print_r:true}</pre>{/if}

{if !empty($duel_echo)}<textarea class="duelecho">{$duel_echo|@print_r:true}</textarea>{/if}
{if !empty($duel_text)}<textarea class="dueltext">{$duel_text|@print_r:true}</textarea>{/if}

<a class="btn" href="./">Okay</a>

{if isset($smarty.get.failure)}<div class="message-failure">{$smarty.get.failure|default:'Failure'}</div>{/if}
{if isset($smarty.get.success)}<div class="message-success">{$smarty.get.success|default:'Success'}</div>{/if}

<div class="gameboard">
	<div class="tmout">
		{math assign='rd' t=$round.time s=$round.ends equation='s-t'}
		{math assign='rv' t=$smarty.now s=$round.ends equation='round(s)-t'}
		{math assign='rp' rd=$rd rv=$rv s=$round.ends equation='round(10000*rv/rd)/100'}
		<s style="width: {$rp}%"></s>
		<u>{$rv}/{$rd}s {$round.ends|date_format:'%D %T'}</u>
	</div>
	
	<big>Round {$round.num}</big>
	<p>{$round.message|default:'Message'}</p>
	{strip}
	<div class="dueler{if $player1.id==$User.id} dueler-you{/if}" id="player1">
		<p>Player 1: {$player1.login} <b>{$round.p1_hp}</b></p>
		<form action="./" method="get">
			<input type="hidden" name="p" value="{$player1.id}" />
			{foreach from=$player1.spells item='s'}
			<button name="spell" value="{$s.id}" class="spl spl-{$s.id}{if $s.id==$round.p1_turn} st-active{/if}" title="{$s.name}"></button>
			{/foreach}
		</form>
	</div>
	{/strip}

	{strip}
	<div class="dueler{if $player2.id==$User.id} dueler-you{/if}" id="player2">
		<p>Player 2: {$player2.login} <b>{$round.p2_hp}</b></p>
		<form action="./" method="get">
			<input type="hidden" name="p" value="{$player2.id}" />
			{foreach from=$player2.spells item='s'}
			<button name="spell" value="{$s.id}" class="spl spl-{$s.id}{if $s.id==$round.p2_turn} st-active{/if}" title="{$s.name}"></button>
			{/foreach}
		</form>
	</div>
	{/strip}

</div>

{include file='_outro.tpl'}