<?require_once $_SERVER['DOCUMENT_ROOT']."/json/config.php";?>
<?require_once $_SERVER['DOCUMENT_ROOT']."/json/conf.function.php";?>
<?require_once $_SERVER['DOCUMENT_ROOT']."/json/init_DB.php";?>
<?define('DOMAIN',"http://".$_SERVER['HTTP_HOST']."/");?>

<?
	$res = "";
	$msg = "";
	$rowCnt = 0;
	$sql = "";
	switch($_REQUEST["cKind"]){
		case "idChk":
			$to = $_REQUEST['userid'];

			$sql = "SELECT * FROM ex_coin_user WHERE auth='".$to."'";
			$rowCnt = $database->num_rows($sql);

			$msg="사용가능한 아이디입니다.";
			$res = "SUCCESS";
			if($rowCnt>0){
				$msg="이미 등록된 아이디입니다.";
				$res = "FLASE";
			}
			break;
	}

	jsonData($res, $msg, $rowCnt, $sql);

	function jsonData($res, $msg, $rowCnt, $sql){
		$arr = array ("state"=>$res, "msg"=>$msg, "cnt"=>$rowCnt, "sql"=>$sql);
		echo json_encode($arr);	
	}
?>