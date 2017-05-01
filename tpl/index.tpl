{include file='_intro.tpl'}

<p><u class="timeline"></u></p>

{strip}
<p>
	{assign 'ts' $smarty.now-4}
	{assign 'tn' $smarty.now+8}
	<u class="timecirc" data-timer="90" data-start="{$ts}" data-ends="{$tn}">
		{*
		<s><s></s></s>
		<s><s></s></s>
		<b>00:02</b>
		*}
	</u>
</p>
{/strip}

<div class="message-success mask-grid">Message SUCCESS</div>
<div class="message-failure">Message FAILURE</div>
<div class="message-warning">Message WARNING</div>

<a class="btn">Link</a>
<button class="btn">Button</button>
<input class="btn" type="button" value="Button" />
<input class="inp" type="text" value="text" />
<select>
{section name='select' loop=12}<option>Option</option>{/section}
</select>
<textarea>Погрешность нестабильна. Уравнение малых колебаний вращает курс. Математический маятник, несмотря на внешние воздействия, трансформирует резонансный угол курса. Уравнение малых колебаний относительно.
Гироскопический стабилизатоор искажает силовой трёхосный гироскопический стабилизатор, игнорируя силы вязкого трения. Внешнее кольцо велико. Систематический уход устойчив. Вращение известно. Управление полётом самолёта поступательно проецирует нестационарный ротор, что имеет простой и очевидный физический смысл.
</textarea>

{foreach item='h' from=array(1,2,3,4,5,6)}
<h{$h}>Header {$h}</h{$h}>
{/foreach}

<p>Погрешность <a href="./">нестабильна</a>. Уравнение малых колебаний вращает курс. Математический маятник, несмотря на внешние воздействия, трансформирует резонансный угол курса. Уравнение малых колебаний относительно.</p>
<p>Гироскопический стабилизатоор искажает силовой трёхосный гироскопический стабилизатор, игнорируя силы вязкого трения. Внешнее кольцо велико. Систематический уход устойчив. Вращение известно. Управление полётом самолёта поступательно проецирует нестационарный ротор, что имеет простой и очевидный физический смысл.</p>

{* <pre>{if !empty($echo)}{$echo}{else}INDEX{/if}</pre>
*}
<div class="divover">
Divover
</div>

{include file='_outro.tpl'}