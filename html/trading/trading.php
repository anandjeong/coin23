<?php
	session_start();
 

 	require_once $_SERVER['DOCUMENT_ROOT']."/json/init_DB.php"; 
 
	$table = "trading";
	 
	@$recDate = gmdate("Y-m-d H:i:s", time()); 
	$params = array(); 
 
 	$trading_uID = @$_POST['data']['trading_uID'];
    if(@$trading_uID == "")
    {
    	echo "userID Error" ;
    	exit;
    }else{
		 
			//echo @$_POST['userData'];//$data_object[0];

		//$trading_data = @$_POST['data'];
		//$params['trading_state'] 	= $trading_data['trading_state'];

		$params['trading_uID'] 		= @$_POST['data']['trading_uID'];
		$params['Trader_wallet'] 	= @$_POST['data']['myWallet'];

		$params['buyer_uID'] 		= "";
		$params['buyer_wallet'] 	= "";

		$params['coin_name'] 		= @$_POST['data']['coin_name'];

		$params['trading_kind'] 	= @$_POST['data']['trading_state'];
		$params['trading_money'] 	= @$_POST['data']['trading_money']?@$_POST['data']['trading_money']:0;
		$params['trading_amount'] 	= @$_POST['data']['trading_amount']?@$_POST['data']['trading_amount']:0;

		$params['buyer_money'] 		= 0;
		$params['buyer_amount'] 	= 0;

		$params['trading_date'] 	= @$_POST['data']['trading_time']?@$_POST['data']['trading_time']:$recDate;
		$params['buying_date'] 		= $recDate;
		$params['account_date'] 	= $recDate;
		$params['dealState'] 		= 0; 
		$params['trade_state'] 			= 0;
 

	 //  $add_query =0;
		// if($content != "")
			 $add_query = $database->insert( $table, $params );
	 
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