	<?
	$cKind = $_REQUEST["cKind"];
	$idx = $_REQUEST["idx"];

	$susuryo = 0;
	$strSql = "";

	switch($cKind){
		case "requestConfirm" :
			$strSql = "SELECT * FROM ex_bank_history WHERE idx=$idx";
			$results = $database->get_row($strSql);
			$user_id = $results[1];
			$symbol = $results[2];
			$balance = $results[7];
			$assetsType = $results[8];

			$strSql = "";

			$chColumn = $symbol=="KRW"?"point":$symbol;

			if($assetsType=="0"){
				$strSql = "UPDATE ex_coin_account SET $chColumn=$chColumn+$balance WHERE auth='$user_id'";
				$msg = "입금처리가 완료 되었습니다.";
			}else{
				$strSql = "SELECT * FROM ex_coin_account WHERE auth='$user_id' AND $chColumn>=($balance+$susuryo)";
				$results = $database->num_rows($strSql);
				$strSql = "";
				if($results>0){
					$strSql = "UPDATE ex_coin_account SET $chColumn=$chColumn-($balance+$susuryo) WHERE auth='$user_id' AND $chColumn>=($balance+$susuryo)";
					$msg = "출금처리가 완료되었습니다.";
				}else{
					$msg = "보유금액이 부족하여 출금이 이루어지지 않았습니다.";
				}
			}

			if($strSql!=""){
				$results = $database->query($strSql);
			}

			if($results>0){
				$strSql = "UPDATE ex_bank_history SET flag=1 WHERE idx=$idx";
				$results = $database->query($strSql);
			}

			if($results>0){
				$st = 0;
			}else{
				$st = 1;
			}

/*			
			$strSql = "SELECT * FROM ex_coin_account WHERE auth='$user_id'";
			$results = $database->get_row($strSql);
			var_dump($results);
*/
			break;
	}

	$arr = array ("state"=>$st, "msg"=>$msg, "strSql"=>$strSql);
	echo json_encode($arr);
?>