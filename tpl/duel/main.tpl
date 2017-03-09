{include file='_intro.tpl'}

<div class="gameboard">
	
	<p>{$player1.alias}</p>
	<p>{$player2.alias}</p>
	
	{strip}
	{assign 'pet' $player1_pets|@current}
	<div class="petpanel" id="petpanel">
		<img src="/img/dragon.jpg" />
		<ins><s></s><i>pet#{$pet.id} 750/1000</i></ins>
		<button data-spell="{$pet.spell1.id}">{$pet.spell1.name}</button>
		<button data-spell="{$pet.spell2.id}">{$pet.spell2.name}</button>
		<button data-spell="{$pet.spell3.id}">{$pet.spell3.name}</button>
	</div>
	{/strip}

	{strip}
	{assign 'pet' $player2_pets|@current}
	<div class="petpanel rgt">
		<img src="/img/dragon.jpg" />
		<ins><s></s><i>pet#{$pet.id} 750/1000</i></ins>
		<button data-spell="{$pet.spell1.id}">{$pet.spell1.name}</button>
		<button data-spell="{$pet.spell2.id}">{$pet.spell2.name}</button>
		<button data-spell="{$pet.spell3.id}">{$pet.spell3.name}</button>
	</div>
	{/strip}

</div>

{include file='_outro.tpl'}