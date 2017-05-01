<!doctype html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Cherio</title>
    <meta name="description" content="Cherio">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" href="/fonts/font-awesome-4.7.0/css/font-awesome.min.css" />
	<link rel="stylesheet" href="/css/_fonts.css" />
	<link rel="stylesheet" href="/css/css.css?v={$smarty.now}" />
	<link rel="stylesheet" href="/css/etc.css?v={$smarty.now}" />
	
	<link rel="stylesheet" href="/css/ui.timeline.css?v={$smarty.now}" />
	<script type="text/javascript" src="/js/ui.timeline.js?v={$smarty.now}"></script>

	<script type="text/javascript" src="/js/utils.js"></script>
	<script type="text/javascript" src="/js/ajax.js"></script>
	<script type="text/javascript" src="/js/js.js"></script>

	{if empty($URL[0])}	
	{else if $URL[0]=='knb'}	
		<link href="/assets/knb/css.css?v={$smarty.now}" rel="stylesheet" />
		<script src="/assets/knb/js.js?v={$smarty.now}" type="text/javascript"></script>
	{else if $URL[0]=='duel'}	
		<link href="/assets/duel/css.css?v={$smarty.now}" rel="stylesheet" />
		<script src="/assets/duel/js.js?v={$smarty.now}" type="text/javascript"></script>
	{/if}
</head>
<body>
{if !empty($echo)}<pre class="phpecho" onclick="this.parentNode.removeChild(this)">{$echo|@print_r:true}</pre>{/if}
<div>
	{strip}
	<header>
	{if empty($User)}
		<a href="/auth/"><i class="fa fa-lock"></i> Login</a>
	{else}
		<a href="/auth/"><i class="fa fa-user-circle-o"></i> {$User.login}</a>

		{*<a><i class="fa fa-power-off"></i>Connect</a>*}

		{if !empty($User.duel)}
		<a href="/duel/"><i class="fa fa-hand-spock-o"></i>Duel</a>
		{else}
		<a href="/duel/init/"><i class="fa fa-hand-spock-o"></i>Init duel</a>
		{/if}
		
		<a href="/duel/list/"><i class="fa fa-handshake-o"></i>Duels list</a>

		<a href="/html/"><i class="fa fa-code"></i>HTML</a>
	
	{/if}
	</header>
	{/strip}

	<div class="content">