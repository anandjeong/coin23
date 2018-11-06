<?
//	echo $_REQUEST["cUrl"];

//	$ch = curl_init("http://203.235.28.219/CreateAccountBTC.html?label=knstorm02");
	$ch = curl_init("https://wt-e79da0981ef1f312f9ba7d4331653992-0.run.webtask.io/generate-ethereum-account");
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$output = curl_exec($ch);      
	curl_close($ch);
	echo json_encode($output);
?>