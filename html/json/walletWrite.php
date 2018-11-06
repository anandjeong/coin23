<?require_once $_SERVER['DOCUMENT_ROOT']."/json/config.php";?>
<?require_once $_SERVER['DOCUMENT_ROOT']."/json/conf.function.php";?>
<?require_once $_SERVER['DOCUMENT_ROOT']."/json/init_DB.php";?>
<?define('DOMAIN',"http://".$_SERVER['HTTP_HOST']."/");?>

<?
	$symbol = $_REQUEST["symbol"];
	$addr = $_REQUEST["addr"];
	$priKey = $_REQUEST["priKey"];
	$id = $_REQUEST["id"];


	if($addr==""){
		$strSql = "";
		$st = 1;
		$msg = $symbol." 지갑생성중 오류가 발생하셨습니다.";
		jsonData($st, $msg, $strSql);
		exit;
	}


	$strSql = "SELECT * FROM ex_coin_account WHERE auth='".$id."'  AND ".$symbol."_address is NULL";
	$cnt = $database->num_rows($strSql);

	$strSql = "UPDATE ex_coin_account SET ".$symbol."_address = '".$addr."' WHERE auth='".$id."'  AND ".$symbol."_address is NULL";
	$results = $database->query($strSql);

	if($cnt>0){

		$sql = "INSERT INTO ex_walletHistory SET ";
		$sql.= "auth ='".$id."' ";
		$sql.= ",symbol ='".$symbol."' ";
		$sql.= ",address ='".$addr."' ";
		$sql.= ",private ='".$priKey."' ";
		$sql.= ",regip ='".$_SERVER['REMOTE_ADDR']."' ";
		$sql.= ",regdate ='".time()."' ";
		$database->query($sql);

		$strSql = "SELECT * FROM ex_coin_account WHERE auth='".$id."'  AND ".$symbol."_address is NULL";

		$st = 0;
		$msg = $symbol." 지갑생성이 완료되었습니다.";

		if($symbol=="BTC"){
			$_SESSION["btcAddr"]=$addr;
		}else{
			$_SESSION["ethAddr"]=$addr;
		}
	}else{
		$st = 1;
		$msg = $symbol." 지갑생성중 오류가 발생하셨습니다.";
	}

	jsonData($st, $msg, $strSql);


	function jsonData($st, $msg, $strSql){
		$arr = array ("state"=>$st, "msg"=>$msg, "query"=>$strSql);
		echo json_encode($arr);	
	}


?>