</div>
<footer>
	<a>{$smarty.const.SSID|default:'SSID'}</a>
	<a href="/auth/">{if empty($User)}Login{else}{$User.name}{/if}</a>
	{* <a href="http://fortawesome.github.io/Font-Awesome/3.2.1/icons/">Font&ndash;Awesome</a> *}
</footer>
</body>
</html>