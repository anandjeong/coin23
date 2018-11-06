<?
	session_start();

//	echo session_cache_expire();
	$pAry = ["login.html", "register.html", "forgotPassword.html"];

	$http_host = $_SERVER['HTTP_HOST'];
	$request_uri = $_SERVER['REQUEST_URI'];
	$dName = explode("?", explode("/", $request_uri)[1])[0];

//	echo $dName;
//	echo array_find($dName, $pAry);
//	exit;
	$login = false;
	if(!isset($_SESSION['uID'])){
		if($dName!="" && $dName!="index.html"){
			if(array_find($dName, $pAry)<-1){
				if($dName!="custom.html" && $dName!="help.html"){
					echo "<script>";
					echo "	alert('로그인 후 서비스를 이용하세요.');";
					echo "	location.href='/login.html';";
					echo "</script>";
				}
			}
		}
	}else{
		$login = true;
		if(array_find($dName, $pAry)>-1){
			echo "<script>";
			echo "	alert('잘못된 접근입니다.');";
			echo "	location.href='/wallets.html';";
			echo "</script>";
		}

		$sesId = $_SESSION["uID"];
		$sesName = $_SESSION["uNAME"];
		$sesBtc = $_SESSION["btcAddr"];
		$sesEth = $_SESSION["ethAddr"];
	}

	function array_find($needle, $pAry){
		foreach ($pAry as $key => $value) {
			if (false !== stripos($value, $needle)) {
				return $key;
			}
		}
	}

//	session_destroy();
?>