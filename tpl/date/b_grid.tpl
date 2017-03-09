{assign 'size' $range}
{assign 'size_rumgroup' 5}{* Кол-во комнат *}
{assign 'size_rumplace' 5}{* Кол-во коек в комнате *}

{* Плашка брони: Комната, Койка, Продолжительность, Дата начала *}
{assign 'guest_room' 1}
{assign 'guest_doss' 3}
{assign 'guest_time' 4}
{assign 'guest_date' '03/08'}

<div class="daygrid" id="daygrid">
<div>
{strip}

	<div class="dg-blank"><input type="range" id="ranger" min="8" max="36" value="{$range}" step="7"></div>

	<div class="dg-mons">
	{assign 'mt' $xdate|date_format:'%Y-%m-01 -1 month'}
	{section name='mt' loop=4}
		{if $xdate|date_format:'%m'==$mt|date_format:'%m'}<a class="amons">{$mt|date_format:'%B'}</a>
		{else}<a class="amons" href="/date/?day={$mt|date_format:'%d-%m-%Y'}">{$mt|date_format:'%B'}</a>
		{/if}
		{assign 'mt' $mt|date_format:'%Y-%m-%d +1 month'}
	{/section}
	</div>

	<div class="dg-dayz fixed-top">
		<ul class="gridrow gridrow-th">
			{assign 'xd' $xdate-86400}
			{section name='rd' loop=$size}
			<li class="{if 0==$xd|date_format:'%w' || 6==$xd|date_format:'%w'}wknd{else}wkpg{/if}{if $xd==$today} tday{/if}">
				{if $xd==$today}Today{else}{$xd|date_format:'%a %d'}{/if}
				{assign 'xd' $xd+86400}
			</li>
			{/section}
		</ul>
	</div>

	<div class="dg-rumz fixed-lft">
		{section loop=$size_rumgroup name='rr'}
		<span class="rumgroup">Rumgroup</span>
			{section loop=$size_rumplace name='rp'}
			<span class="rumplace" data-groupid="grp{$smarty.section.rr.iteration}">Doss {$smarty.section.rp.iteration}</span>
			{/section}
		{/section}
	</div>

	<div class="dg-grid">
		{section loop=$size_rumgroup name='gs'}
			<ul class="gridrow gridrow-rg">
				{assign 'xd' $xdate-86400}
				{section name='rd' loop=$size}
				<li class="{if 0==$xd|date_format:'%w' || 6==$xd|date_format:'%w'}wknd{else}wkpg{/if}{if $xd==$today} tday{/if}">
					<span class="rumgroup-count">{$xd|date_format:'%w'}</span>
					{assign 'xd' $xd+86400}
				</li>
				{/section}
			</ul>
			{section loop=$size_rumplace name='gg'}
			<ul class="gridrow gridrow-dt">
				{assign 'zd' $xdate-86400}
				{section name='rd' loop=$size}
				<li class="{if 0==$zd|date_format:'%w' || 6==$zd|date_format:'%w'}wknd{else}wkpg{/if}{if $zd==$today} tday{/if}">
					{if $smarty.section.gs.iteration==$guest_room &&
						$smarty.section.gg.iteration==$guest_doss &&
						$guest_date==$zd|date_format:'%d/%m'}
						<div class="gest" style="width: calc(100%*{$guest_time})">{$guest_time} days guest</div>
					{else}
						<dfn>Bed{$smarty.section.gg.iteration}<br>{$zd|date_format:'%d/%m'}</dfn>
					{/if}
					{assign 'zd' $zd+86400}
				</li>
				{/section}
			</ul>
			{/section}

		{/section}
	</div>

{/strip}
</div>
</div>