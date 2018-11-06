<?require_once $_SERVER['DOCUMENT_ROOT']."/json/config.php";?>
<?require_once $_SERVER['DOCUMENT_ROOT']."/json/conf.function.php";?>
<?require_once $_SERVER['DOCUMENT_ROOT']."/json/init_DB.php";?>
<?define('DOMAIN',"http://".$_SERVER['HTTP_HOST']."/");?>

<?
	$cKind = $_REQUEST["cKind"];
	$res = "";
	$sql = "";
	$msg = "";
	$pattern = "/^.*(?=^.{10,20}$)(?=.*\d)(?=.*[a-zA-Z])(?=.*[*!@#$%^&+=]).*$/";

	if(preg_match($pattern ,$_REQUEST['pwd'])){
		$userpw=HASH("SHA512", trim($_REQUEST['pwd']) );
		switch($cKind){
			case "signUp":
				//show_msg("회원가입서버 점검중입니다. 잠시후 이용하세요");	exit;
				$useremail=trim($_REQUEST['email']);
				$userid=trim($_REQUEST['uId']);
				$username=trim($_REQUEST['uName']);
//				$userpw=HASH("SHA512", trim($_REQUEST['pwd']) );
				$userhp=trim($_REQUEST['hp']);
				$recoId=trim($_REQUEST['recoId']);


				$token=md5(time());
				if(!$useremail || !$userid || !$_REQUEST['pwd'] || !$token){
					$msg = "잘못된 접근입니다";
				} else {
					$sql = "SELECT * FROM ex_coin_user WHERE auth='".$userid."' OR email='".$useremail."' ";
					$res = $database->num_rows($sql);
					if($res > 0){
						$msg = "이미 가입된 정보가 있습니다.";
					} else {
						$sql = "INSERT INTO ex_coin_user SET ";
						$sql.= "auth ='".$userid."' ";
						$sql.= ",pass ='".$userpw."' ";
						$sql.= ",authkey ='".$token."' ";
						$sql.= ",username ='".$username."' ";
						$sql.= ",mobile ='".$userhp."' ";
						$sql.= ",email ='".$useremail."' ";
						$sql.= ",sns ='' ";
						$sql.= ",snskey ='' ";
						$sql.= ",regip ='".$_SERVER['REMOTE_ADDR']."' ";
						$sql.= ",regdate ='".time()."' ";
						$sql.= ",upddate ='0' ";
						$sql.= ",logindate ='".time()."' ";
						$sql.= ",failcount ='0' ";
						$sql.= ",loginlock ='0' ";
						$sql.= ",sms_alarm ='0' ";
						$sql.= ",status ='1' ";
						$sql.= ",recoId ='".$recoId."' ";

						$res=$database->query($sql);
						if(!$res){
							backup_save_log(mysqli_error());
							//echo mysql_error();
							$msg = "회원가입 중 오류가 발생하였습니다. 잠시후 다시 이용해주세요";
							exit;
						} else {
							$sql = "INSERT INTO ex_coin_account SET ";
							$sql.= "auth ='".$userid."' ";
							$sql.= ",authkey ='".$token."' ";
							$sql.= ",authpass ='".$userpw."' ";
							//$sql.= ",BTC_address ='".$address."' ";
							$sql.= ",regip ='".$_SERVER['REMOTE_ADDR']."' ";
							$sql.= ",regdate ='".time()."' ";
							$sql.= ",status ='1' ";
							$res=$database->query($sql);
							if($res==true) $res="SUCCESS";
							if(!$res) backup_save_log("ERR".mysqli_error());
							
							$msg = "회원가입이 완료되었습니다.\n서비스 이용을 위해 로그인을 해 주세요.";

							$sql = "UPDATE ex_coin_auth";
							$sql = $sql." SET";
							$sql = $sql." status=9";
							$sql = $sql." WHERE auth='".$useremail."'";
							$database->query($sql);
						}
					}
				}

				break;
			case "infoModify":
				$userid=trim($_REQUEST['email']);
				$userpw=HASH("SHA512", trim($_REQUEST['pwd']) );

				$sql = "UPDATE ex_coin_user";
				$sql = $sql." SET";
				$sql = $sql." pass='".$userpw."'";
				$sql = $sql." WHERE email='".$userid."'";
				$res=$database->query($sql);
				if($res==true) $res="SUCCESS";
				if(!$res) backup_save_log("ERR".mysqli_error());
				$msg="비밀번호 변경이 완료 되었습니다.";

				$sql = "UPDATE ex_coin_auth";
				$sql = $sql." SET";
				$sql = $sql." status=9";
				$sql = $sql." WHERE auth='".$userid."'";
				$database->query($sql);
				break;
		}
	} else {
		backup_save_log("ERROR 비밀번호 규칙오류 ".$_REQUEST['pwd']);
		$msg = "비밀번호는 영문,숫자,특수문자를 포함하여 10자리 이상 입력하셔야 합니다. 다시입력해주세요";
	}

	$arr = array ("state"=>$res, "msg"=>$msg, "strSql"=>$sql);
	echo json_encode($arr);
?>