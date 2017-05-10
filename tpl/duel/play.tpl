{include file='_intro.tpl'}
<script type="text/javascript" src="/js/play.js"></script>

{if !empty($duel_data)}<pre class="dueldata">{$duel_data|@print_r:true}</pre>{/if}
{if !empty($duel_echo)}<textarea class="duelecho">{$duel_echo|@print_r:true}</textarea>{/if}
{if !empty($duel_text)}<textarea class="dueltext">{$duel_text|@print_r:true}</textarea>{/if}

<a class="btn" href="./">Okay</a>

{if isset($smarty.get.failure)}<div class="message-failure">{$smarty.get.failure|default:'Failure'}</div>{/if}
{if isset($smarty.get.success)}<div class="message-success">{$smarty.get.success|default:'Success'}</div>{/if}

{if $player1.id==$User.id}
	{assign 'you' $player1}
	{assign 'foe' $player2}
	{assign 'you_turn' $round.p1_turn}
	{assign 'foe_turn' $round.p2_turn}
{else}
	{assign 'you' $player2}
	{assign 'foe' $player1}
	{assign 'you_turn' $round.p2_turn}
	{assign 'foe_turn' $round.p1_turn}
{/if}

{strip}
<div class="gameboard">
	
	<div class="round-timer">
		{math assign='rv' t=$smarty.now s=$round.ends equation='round(s-t)'}
		{assign 'ts' $round.time}
		{assign 'tn' $round.ends}
		<u class="timecirc" data-timer="{$rv}" data-start="{$ts}" data-ends="{$tn}"></u>
	</div>

	{math assign='p1hp' m=120 x=$round.p1_hp equation='round(100*x/m)'}
	{math assign='p2hp' m=120 x=$round.p2_hp equation='round(100*x/m)'}
	<div class="dueler dueler-{if $player1.id==$User.id}you{else}foe{/if}">
		<p>
			<s style="width:{$p1hp}%"></s>
			<b>{$player1.login}</b>
			<u>{$round.p1_hp}</u>
		</p>
	</div>
	<div class="dueler dueler-{if $player2.id==$User.id}you{else}foe{/if}">
		<p>
			<s style="width:{$p2hp}%"></s>
			<b>{$player2.login}</b>
			<u>{$round.p2_hp}</u>
		</p>
	</div>

	<form action="./" method="get" class="gbd-panel">
		<input type="hidden" name="p" value="{$you.id}" />
		{foreach from=$you.spells item='s'}
		<button name="spell" value="{$s.id}" class="spl spl-{$s.id}{if $s.id==$you_turn} st-active{/if}" title="{$s.name}"></button>
		{/foreach}
	</form>

	<form action="./" method="get" class="gbd-enemy">
		<input type="hidden" name="p" value="{$foe.id}" />
		{foreach from=$foe.spells item='s'}
		<button name="spell" value="{$s.id}" class="spl spl-{$s.id}{if $s.id==$foe_turn} st-active{/if}" title="{$s.name}"></button>
		{/foreach}
	</form>

	<div class="round-message" id="round-message">RMSG</div>


</div>
{/strip}
<script>
var befor = {$round.before};
console.log(befor.text);
var rmsg = document.getElementById('round-message');
rmsg.innerText = befor.text;
</script>
{include file='_outro.tpl'}