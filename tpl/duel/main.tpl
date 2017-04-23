{include file='_intro.tpl'}

<h3>Combat</h3>

{if empty($User)}
	<a class="btn" href="/auth/">Войти</a>
{elseif empty($User.duel)}
	У вас нет активных поединков <a class="btn" href="/duel/init/">Начать</a>
{else}
	Вы в бою <a class="btn" href="./play/">Продолжить</a>
{/if}

{include file='_outro.tpl'}