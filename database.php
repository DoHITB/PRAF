<?php
include 'data.php';

function __store($gt, $gs, $id){
	$gsf = _compact($gs);
	$connection = mysql_select_db(__data('database'), ($con = mysql_connect(__data('server'), __data('user'), __data('pass'))));
	
	if($id === 0){		
		mysql_query("INSERT INTO PARDATA(PARPIV, PARPAT) VALUES ('" . mysql_real_escape_string($gt)	. "', '" . mysql_real_escape_string($gsf)  . "');");
		
		$ret = array(mysql_insert_id(), $gsf);
	}else{
		mysql_query("UPDATE PARDATA SET PARPIV = '" . mysql_real_escape_string($gt) . "', PARPAT = '" . mysql_real_escape_string($gsf) . "' WHERE PARID = " . mysql_real_escape_string($id) . ";");
		
		$ret = array($id, $gsf);
	}

	mysql_close($con);
	
	return $ret;
}

function __get($id){
	$connection = mysql_select_db(__data('database'), ($con = mysql_connect(__data('server'), __data('user'), __data('pass'))));
	
	$r = mysql_query("SELECT PARPIV, PARPAT FROM PARDATA WHERE PARID = " . mysql_real_escape_string($id) . ";");
	$c = mysql_fetch_array($r, MYSQL_NUM);
	
	mysql_close($con);

	return array($c[0], $c[1]);
}
?>