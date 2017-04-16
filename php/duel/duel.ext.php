<?php
function changeGameState($state, $player1sid, $player2sid) {
	$cs = array_merge(array(), $state);
	$ns = array_merge(array(), $state);
	$ns['player1'] = $cs['player1'] - 5;
	$ns['player2'] = $cs['player2'] - 7;

	$rock_paper = 'Бумага заворачивает камень';
	$rock_scissors = 'Камень разбивает ножницы';
	$rock_lizard = 'Камень давит ящерицу';
	$rock_spock = 'Спок испаряет камень';
	$paper_scissors = 'Ножницы режут бумагу';
	$paper_lizard = 'Ящерица ест бумагу';
	$paper_spock = 'Бумага подставляет Спока';
	$scissors_lizard = 'Ножницы разрезают ящерицу';
	$scissors_spock = 'Спок ломает ножницы';
	$lizard_spock = 'Ящерица травит Спока';

	$win = 0;
	$txt = '';
	switch ($player1sid) {
	case 'rock':
		if($player2sid=='paper')	{$win = 2;$txt = $rock_paper;}
		if($player2sid=='scissors') {$win = 1;$txt = $rock_scissors;}
		if($player2sid=='lizard')	{$win = 1;$txt = $rock_lizard;}
		if($player2sid=='spock')	{$win = 2;$txt = $rock_spock;}
	break;
	case 'paper':
		if($player2sid=='rock')		{$win = 1;$txt = $rock_paper;}
		if($player2sid=='scissors') {$win = 2;$txt = $paper_scissors;}
		if($player2sid=='lizard')	{$win = 2;$txt = $paper_lizard;}
		if($player2sid=='spock')	{$win = 1;$txt = $paper_spock;}
	break;
	case 'scissors':
		if($player2sid=='rock')		{$win = 2;$txt = $rock_scissors;}
		if($player2sid=='paper')	{$win = 1;$txt = $paper_scissors;}
		if($player2sid=='lizard')	{$win = 1;$txt = $scissors_lizard;}
		if($player2sid=='spock')	{$win = 2;$txt = $scissors_spock;}
	break;
	case 'lizard':
		if($player2sid=='rock')		{$win = 2;$txt = $rock_lizard;}
		if($player2sid=='paper')	{$win = 1;$txt = $paper_lizard;}
		if($player2sid=='scissors')	{$win = 2;$txt = $scissors_lizard;}
		if($player2sid=='spock')	{$win = 1;$txt = $lizard_spock;}
	break;
	case 'spock':
		if($player2sid=='rock')		{$win = 1;$txt = $rock_spock;}
		if($player2sid=='paper')	{$win = 2;$txt = $paper_spock;}
		if($player2sid=='scissors')	{$win = 1;$txt = $scissors_spock;}
		if($player2sid=='lizard')	{$win = 2;$txt = $lizard_spock;}
	break;
	}
	$ns['echo'] = 'win:'.$win.' txt:'.$txt;
	return $ns;
}
?>