<?php
 
	require_once $_SERVER['DOCUMENT_ROOT']."/json/init_DB.php"; 
 
	$table = "trading";
	 
	$recDate = gmdate("Y-m-d H:i:s", time()); 
	$params = array(); 
	$buyer_uID = trim(@$_POST['data']['buyer_uID']);
	$idx = @$_POST['data']['idx']; 


    if(@$buyer_uID == "")
    {
    	echo "[ Error] buyer_uID 가 없습니다." ;
    	exit;

    }else{
 	 
		$query_01 = " SELECT trading_uID as oderID, coin_name, trading_money  FROM $table WHERE idx ='$idx'  ";
		list( $trading_uID ,$coin_name , $trading_money) = $database->get_row($query_01);
	 

		$trading_uID = trim($trading_uID);
		$amount = $_POST['data']['buyer_amount'];
		$trading_kind  = @$_POST['data']['trading_kind'];
		 

		$params['buyer_uID'] 		= @$buyer_uID;
		$params['buyer_wallet'] 	= @$_POST['data']['buyer_allet']; 
		$params['coin_name'] 		= @$coin_name;
		$params['buyer_money'] 		= @$trading_money;
		$params['buyer_amount'] 	= @$amount; 
		$params['buying_date'] 		= @$recDate; 
		$params['dealState'] 		= 1; 
		$params['trade_state'] 		= 1;  
  

	$where['idx'] = $idx; 
	$add_query = $database->update( $table , $params, $where , 1 );


	$TABLE_account = "ex_coin_account";
 

	$query = " SELECT MCC as t_MCC, point as t_point FROM $TABLE_account WHERE auth ='$trading_uID'  ";
	list( $t_MCC, $t_point) = $database->get_row($query);
	
	$query2 = " SELECT MCC as b_MCC, point as b_point  FROM $TABLE_account WHERE auth ='$buyer_uID'  ";
	list( $b_MCC, $b_point) = $database->get_row($query2);
 

	$tableAccount ="ex_coin_account";

 	$where_order['auth'] = @$trading_uID;
	$where_buyer['auth'] = @$buyer_uID; 
	$deal_money =  $amount * $trading_money;

	if($trading_kind =="B")
	{
		$params_order['MCC'] = $t_MCC + $amount;
		$params_buyer['MCC'] = $b_MCC - $amount;  

		$params_order['point'] = $t_point - $deal_money;
		$params_buyer['point'] = $b_point + $deal_money; 

	}else{
		
		$params_order['MCC'] = $t_MCC - $amount;
		$params_buyer['MCC'] = $b_MCC + $amount; 

		$params_order['point'] = $t_point + $deal_money;
		$params_buyer['point'] = $b_point - $deal_money; 
  	} 

	$add_query_a = $database->update( $tableAccount , $params_order, $where_order , 1 );
	$add_query_b = $database->update( $tableAccount , $params_buyer, $where_buyer , 1 );


		if( $add_query )
		{
			// echo '<p>Successfully inserted &quot;'. $params['subject']. '&quot; into the database.</p>';
				$last = $database->lastid();
				echo $last ;
		}else
			echo "Error" ;
		}
 
	function fixtags($text){
		$text = htmlspecialchars($text);
		$text = preg_replace("/=/", "=\"\"", $text);
		$text = preg_replace("/&quot;/", "&quot;\"", $text);
		$tags = "/&lt;(\/|)(\w*)(\ |)(\w*)([\\\=]*)(?|(\")\"&quot;\"|)(?|(.*)?&quot;(\")|)([\ ]?)(\/|)&gt;/i";
		$replacement = "<$1$2$3$4$5$6$7$8$9$10>";
		$text = preg_replace($tags, $replacement, $text);
		$text = preg_replace("/=\"\"/", "=", $text);
		return $text;
	} 
?>