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
		case "emailChk":
			$to = $_REQUEST['email'];

			$sql = "SELECT * FROM ex_coin_user WHERE email='".$to."'";
			$rowCnt = $database->num_rows($sql);
			if($rowCnt>0){
				$msg="이미 등록된 이메일입니다.";
				$res = "FLASE";
				jsonData($res, $msg, $rowCnt, $sql);
				exit;
			}

			$cktime = time() - 300;

			$sql = "SELECT * FROM ex_coin_auth WHERE auth='".$to."' AND auth_level = 0 AND status=0 AND regdate > ".$cktime." ORDER BY idx DESC LIMIT 1";
			$rowCnt = $database->num_rows($sql);
		
			if($rowCnt>0){
				$msg="인증 대기중입니다.";
				$res = "FLASE";
			}else{
				$res = authMailSend($database, $to);
			}
			break;
		case "authCodeChk":
			$to = $_REQUEST['email'];
			$cktime = time() - 300;

			$sql = "SELECT * FROM ex_coin_user WHERE auth='".$to."'";
			$rowCnt = $database->num_rows($sql);
			if($rowCnt>0){
				$sql = "SELECT * FROM ex_coin_auth WHERE auth='".$to."' AND auth_level = 0 AND status=0 AND regdate > ".$cktime." ORDER BY idx DESC LIMIT 1";
				$rowCnt = $database->num_rows($sql);
			
				if($rowCnt>0){
					$msg="인증 대기중입니다.";
					$res = "FLASE";
				}else{
					$res = authMailSend($database, $to);
				}
			}else{
				$msg="존재하지 않는 정보입니다.";
				$res = "FLASE";
			}
			break;
		default:
			$sql = "SELECT * FROM ex_coin_auth WHERE auth='".$_REQUEST["email"]."' AND status = 1 ORDER BY idx DESC LIMIT 1";
			$database->num_rows($sql);
			if($database->num_rows($sql)>0){
				$res = "SUCCESS";
			}else{
				$res = "FLASE";
			}
			break;
	}

	jsonData($res, $msg, $rowCnt, $sql);

	function jsonData($res, $msg, $rowCnt, $sql){
		$arr = array ("state"=>$res, "msg"=>$msg, "cnt"=>$rowCnt, "sql"=>$sql);
		echo json_encode($arr);	
	}

	function authMailSend($database, $to){
		$sql = "SELECT * FROM ex_config WHERE status = 1 ORDER BY no DESC LIMIT 1";
		$results = $database->get_results( $sql );

		$sendMail="";
		foreach( $results as $row )
		{
			$sendMail = $row["send_mail"];
		}

	
		$subject = "본인 인증 메일";

		$headers = 'From: '.$sendMail. "\r\n" .
	  'Reply-To: admin@excube.info' . "\r\n" .
	  'X-Mailer: PHP/' . phpversion();

		$authkey = md5(DOMAIN.$to);
		$auth_val = DOMAIN.time().$to;
		$auth_val = HASH("SHA512", trim($auth_val) );

		$sql = "INSERT INTO ex_coin_auth SET auth='".$to."' ";
		$sql.= ",auth_data = '".$to."' ";
		$sql.= ",authkey = '".$authkey."' ";
		$sql.= ",auth_val = '".$auth_val."' ";
		$sql.= ",regip = '".$_SERVER['REMOTE_ADDR']."' ";
		$sql.= ",regdate = '".time()."' ";
		$sql.= ",status = '0' ";
		$res = $database->query($sql);
		
		// 메세지
		$body = '
		<html>
		<head>
		  <title>회원 이메일 인증</title>
		</head>
		<body>
		  <p>아래 링크를 클릭하여 이메일 주소 인증을 진행해주세요!</p>
		  <p><a href='.DOMAIN.'auth.html?auth='.$authkey.'&tokn='.$auth_val.'>'.DOMAIN.'auth.html?auth='.$authkey.'&tokn='.$auth_val.'</a></p>
		</body>
		</html>
		';
		
		$sql = "SELECT * FROM ex_coin_bbsdata WHERE board='email' AND category='회원인증' ORDER BY num DESC LIMIT 1";
		$results = $database->get_results( $sql );

		$subject = "";
		$message = "";
		foreach( $results as $row )
		{
			$subject = $row["title"];
			$message = $row["content"];
		}
		
		backup_save_log($message);
		
		// HTML 메일을 보내려면, Content-type 헤더를 설정해야 합니다.
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		
		// 추가 헤더
		//$headers .= 'To: Mary <'.$to.'>, Kelly <admin@excube.info>' . "\r\n";
		$headers .= 'To: <'.$to.'>' . "\r\n";
		$headers .= 'From:  <'.$sendMail.'>' . "\r\n";
		//$headers .= 'Cc: heavensdooor@nate.com' . "\r\n";
		//$headers .= 'Bcc: shlove1204@naver.com' . "\r\n";
		// 메일 보내기

		$res = send_email_socks_smtp(SITENAME,$sendMail,$to,$to,$subject,$body);	//$from,$to,$subject."2",$body)
		backup_save_log(" send_email_socks_smtp ###########".$res);

		return $res;
	}
?>