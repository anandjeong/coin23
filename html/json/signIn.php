<?require_once $_SERVER['DOCUMENT_ROOT']."/json/config.php";?>
<?require_once $_SERVER['DOCUMENT_ROOT']."/json/conf.function.php";?>
<?require_once $_SERVER['DOCUMENT_ROOT']."/json/init_DB.php";?>
<?define('DOMAIN',"http://".$_SERVER['HTTP_HOST']."/");?>

<?
	$cKind = $_REQUEST["cKind"];

	$msg = "";
	switch($cKind){
		case "signIn":
			$uId = $_REQUEST["id"];
			$uPwd = HASH("SHA512", trim($_REQUEST["pwd"]) );;

			$sql = "
			SELECT * FROM ex_coin_user a
			JOIN ex_coin_account b on a.auth=b.auth
			WHERE a.auth='$uId' AND a.pass='$uPwd'
			";
			$res=$database->num_rows($sql);

			if($res==1){
				$row = $database->get_row($sql);
				$_SESSION["uID"]=$row[1];
				$_SESSION["uNAME"]=$row[4];
				$_SESSION["btcAddr"]=$row[43];
				$_SESSION["ethAddr"]=$row[46];
				$res="SUCCESS";
			}else{
				$res="FALSE";
				$msg="로그인 정보가 정확하지 않습니다.\n로그인후 서비스 이용이 가능합니다.";
			}
			break;
	
	}

	

	$arr = array ("state"=>$res, "msg"=>$msg, "strSql"=>$sql);
	echo json_encode($arr);
?>