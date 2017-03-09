<?
if(empty($URL_PARAMS[1])) require(ROOT.'php/account/main.php');
else if($URL_PARAMS[1]=='user') require(ROOT.'php/account/user.php');
else if($URL_PARAMS[1]=='heroes') require(ROOT.'php/account/heroes.php');
else require(ROOT.'php/account/main.php');
?>