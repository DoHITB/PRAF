<?php
function __data($i){
	if($i === 'database')
		return 'database';
	elseif($i === 'server')
		return 'server';
	elseif($i === 'user')
		return 'user';
	elseif($i === 'pass')
		return 'password';
	
	return '';
}
?>