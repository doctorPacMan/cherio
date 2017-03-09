{strip}<!DOCTYPE html>
<html lang="en">
<head>
	{assign 'assets' '/assets/date/'}
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="{$assets}favicon.ico" type="image/x-icon">
	<title>booking</title>

	<link rel="stylesheet" href="{$assets}css/css.css">

	<script type="text/javascript" src="{$assets}js/daysgrid.js" async></script>
	<script type="text/javascript" src="{$assets}js/app.js" async></script>

	{* Количество столбцов-дней *}
	{assign 'range' value=$smarty.get.range|default:36}{* Количество столбцов-дней *}
	
	{* Сегодня *}
	{assign 'today' $smarty.now|date_format:'%d-%m-%Y'|@strtotime}
	
	{* Дата с которой работаем *}
	{if empty($smarty.get.day)}{assign 'xdate' $today}
	{else}{assign 'xdate' $smarty.get.day|@strtotime}
	{/if}

</head>
<body><div>
	<section class="heder"><h6>section</h6>
		Today: {$today|date_format:'%D'}
		<br>Date: {$xdate|date_format:'%D %T'}
	</section>
	
	<section class="clndr" data-range="{$range}"><h6>section</h6>
	<div>{include file='date/b_grid.tpl'}</div>
	</section>
</div></body>
</html>
{/strip}