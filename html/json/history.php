<?require_once $_SERVER['DOCUMENT_ROOT']."/json/config.php";?>
<?require_once $_SERVER['DOCUMENT_ROOT']."/json/conf.function.php";?>
<?require_once $_SERVER['DOCUMENT_ROOT']."/json/init_DB.php";?>
<?define('DOMAIN',"http://".$_SERVER['HTTP_HOST']."/");?>
<?
	$auth = $_REQUEST["auth"];
	$symbol = $_REQUEST["symbol"];

	$wh = "";
	if($symbol!="ALL"){
		$wh = " AND symbol='$symbol'";
	}

	$strSql = "SELECT * FROM ex_bank_history WHERE user_id='$auth'$wh ORDER BY idx DESC";

	$results = $database->get_results($strSql);

	jsonData($_REQUEST, "", 0, $strSql, $results);
	function jsonData($res, $msg="", $rowCnt=0, $sql="", $results=""){
		$arr = array ("state"=>$res, "msg"=>$msg, "cnt"=>$rowCnt, "sql"=>$sql, "results"=>$results);
		echo json_encode($arr);	
	}
?>