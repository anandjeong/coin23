<?
	$id = $_REQUEST["id"];
	$pwd = $_REQUEST["pwd"];
	$st=0;
	$msg="데이터체크";
	if($id=="admin" && $pwd=="!123456"){
		$st=1;
	}
	$arr = array ("state"=>$st, "msg"=>$msg, "query"=>$_REQUEST);
	echo json_encode($arr);
?>