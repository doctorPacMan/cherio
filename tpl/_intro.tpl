<!doctype html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Cherio</title>
    <meta name="description" content="Cherio">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="/css/css.css" />
	
	<script type="text/javascript" src="/js/utils.js"></script>
	<script type="text/javascript" src="/js/ajax.js"></script>
	<script type="text/javascript" src="/js/js.js"></script>

	<link href="/assets/knb/css.css?v={$smarty.now}" rel="stylesheet" />
	<script src="/assets/knb/js.js?v={$smarty.now}" type="text/javascript"></script>

</head>
<body>
{if !empty($echo)}<pre class="phpecho">{$echo|@print_r:true}</pre>{/if}
<div>
	<header>
		<button>connect</button>
		{if empty($User)}<a href="/auth/">Login</a>
		{else}
			User: {$User.login} <a href="/auth/?logout&amp;back={'/tick/'|escape:'url'}">Logout</a>
		{/if}
	</header>

