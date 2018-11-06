<?
	$pageType = $pageType==""?"rkList":$pageType;
	switch($pageType){
		case "rkList":
			$strSql = "SELECT * FROM ex_bank_history WHERE symbol='KRW' ORDER BY idx DESC";	
			break;
		case "rcList":
			$strSql = "SELECT * FROM ex_bank_history WHERE symbol<>'KRW' ORDER BY idx DESC";	
			break;
		case "cList":
			$strSql = "SELECT * FROM ex_coin ORDER BY idx ASC";
			break;
		case "mList":
			$strSql = "SELECT * FROM ex_coin_user ORDER BY idx DESC";
			break;
		case "trList1": case "trList2":
			$strSql = "SELECT * FROM trading ORDER BY idx DESC";
			break;
	}
    $result_array = array(); // 결과 값을 담을 변수 생성 

	$results = $database->get_results( $strSql );

	foreach( $results as $row ){
		$result_array[] = $row;
	}

	echo json_encode($result_array);
?>