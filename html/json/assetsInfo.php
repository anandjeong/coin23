<?require_once $_SERVER['DOCUMENT_ROOT']."/json/config.php";?>
<?require_once $_SERVER['DOCUMENT_ROOT']."/json/conf.function.php";?>
<?require_once $_SERVER['DOCUMENT_ROOT']."/json/init_DB.php";?>
<?define('DOMAIN',"http://".$_SERVER['HTTP_HOST']."/");?>

<?
//	echo $_REQUEST["assetsType"]."//".$_REQUEST["user_id"];
	$strSql = "";
	$susuryo = 0;
	if($_REQUEST["assetsType"]=="1"){
		$symbol = $_REQUEST["symbol"];
		if($_REQUEST["symbol"]=="KRW"){
			$symbol = "point";
		}
		$strSql ="SELECT ".$symbol." FROM ex_coin_account WHERE auth='".$_REQUEST["user_id"]."' AND ".$symbol.">=".($_REQUEST["balance"]+$susuryo);
		$rCnt = $database->num_rows($strSql);
	}



	if($_REQUEST["assetsType"]==1){
		if($rCnt>0){
			$strsql = "insert into ex_bank_history";
			$strsql = $strsql." SET";
			$cnt = 0;
			foreach($_REQUEST as $key=>$val){
					if($cnt == 0){
						$strsql = $strsql." ".$key."='".$val."'";
						$cnt++;
					}else{
						$strsql = $strsql.", ".$key."='".$val."'";
					}
			}
			$strsql = $strsql.", regip='".$_SERVER['REMOTE_ADDR']."'";
			$strsql = $strsql.";";

			echo $database->query($strsql);
		}else{
			echo 0; 
		}
	}else{
		$strsql = "insert into ex_bank_history";
		$strsql = $strsql." SET";
		$cnt = 0;
		foreach($_REQUEST as $key=>$val){
				if($cnt == 0){
					$strsql = $strsql." ".$key."='".$val."'";
					$cnt++;
				}else{
					$strsql = $strsql.", ".$key."='".$val."'";
				}
		}
		$strsql = $strsql.", regip='".$_SERVER['REMOTE_ADDR']."'";
		$strsql = $strsql.";";

		echo $database->query($strsql);
	}


?>