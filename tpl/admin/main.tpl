{include file='admin/_intro.tpl'}

<h3>Users</h3>
{if !empty($message)}<p>{$message}</p>{/if}
{include file='admin/b_dbtable.tpl' data=$dbdata}
{include file='admin/f_register.tpl'}


{include file='admin/_outro.tpl'}