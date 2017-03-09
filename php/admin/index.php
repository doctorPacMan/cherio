<?
if(empty($URL_PARAMS[1])) {}
else if($URL_PARAMS[1]=='users') require(ROOT.'php/admin/users.php');
else if($URL_PARAMS[1]=='pets') require(ROOT.'php/admin/pets.php');
?>