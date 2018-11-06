<?
	session_start();	
	session_destroy();

	$arr = array ("state"=>"SUCCESS", "msg"=>$msg, "cnt"=>$rowCnt, "sql"=>$sql);
	echo json_encode($arr);
?>