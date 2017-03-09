MAIN

<link rel="stylesheet" href="{$ROOT}/css/selectah.css" />
<script type="text/javascript" src="{$ROOT}/js/selectah.js"></script>
<div class="selectah">

	<select name="selektah">
	{section name='s' loop='5'}
		<option value="option{$smarty.section.s.iteration}">
			Option {$smarty.section.s.iteration}
		</option>
	{/section}
	</select>

	<ol>
	{section name='u' loop='5'}
		<li>
			Option{$smarty.section.s.iteration}
		</li>
	{/section}
	</ol>

</div>