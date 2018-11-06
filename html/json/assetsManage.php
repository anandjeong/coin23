<?require_once $_SERVER['DOCUMENT_ROOT']."/json/config.php";?>
<?require_once $_SERVER['DOCUMENT_ROOT']."/json/conf.function.php";?>
<?require_once $_SERVER['DOCUMENT_ROOT']."/json/init_DB.php";?>
<?define('DOMAIN',"http://".$_SERVER['HTTP_HOST']."/");?>

<?

	$user_id = $_REQUEST["user_id"];
//	$user_id = "knstorm";


	$strSql = "";
	$strSql = $strSql."SELECT\n";
	$strSql = $strSql."	coin\n";
	$strSql = $strSql."	, case\n";
	$strSql = $strSql."		when coin='KRW' then (SELECT point FROM ex_coin_account WHERE auth='$user_id')\n";
	$strSql = $strSql."		when coin='BTC' then (SELECT BTC FROM ex_coin_account WHERE auth='$user_id')\n";
	$strSql = $strSql."		when coin='ETH' then (SELECT ETH FROM ex_coin_account WHERE auth='$user_id')\n";
	$strSql = $strSql."		when coin='MCC' then (SELECT MCC FROM ex_coin_account WHERE auth='$user_id')\n";
	$strSql = $strSql."		when coin='GAME' then (SELECT GAME FROM ex_coin_account WHERE auth='$user_id')\n";
	$strSql = $strSql."		when coin='RTLT' then (SELECT RTLT FROM ex_coin_account WHERE auth='$user_id')\n";
	$strSql = $strSql."	end as balance\n";
	$strSql = $strSql."	, case\n";
	$strSql = $strSql."		when coin='KRW' then (SELECT SUM(balance) FROM ex_bank_history WHERE user_id='$user_id' AND assetsType='0' AND flag=0 AND symbol='KRW')\n";
	$strSql = $strSql."		when coin='BTC' then (SELECT SUM(balance) FROM ex_bank_history WHERE user_id='$user_id' AND assetsType='0' AND flag=0 AND symbol='BTC')\n";
	$strSql = $strSql."		when coin='ETH' then (SELECT SUM(balance) FROM ex_bank_history WHERE user_id='$user_id' AND assetsType='0' AND flag=0 AND symbol='ETH')\n";
	$strSql = $strSql."		when coin='MCC' then (SELECT SUM(balance) FROM ex_bank_history WHERE user_id='$user_id' AND assetsType='0' AND flag=0 AND symbol='MCC')\n";
	$strSql = $strSql."		when coin='GAME' then (SELECT SUM(balance) FROM ex_bank_history WHERE user_id='$user_id' AND assetsType='0' AND flag=0 AND symbol='GAME')\n";
	$strSql = $strSql."		when coin='RTLT' then (SELECT SUM(balance) FROM ex_bank_history WHERE user_id='$user_id' AND assetsType='0' AND flag=0 AND symbol='RTLT')\n";
	$strSql = $strSql."	end as bal_01\n";
	$strSql = $strSql."	, case when coin='KRW' then (SELECT SUM(balance) FROM ex_bank_history WHERE user_id='$user_id' AND assetsType='1' AND flag=0 AND symbol='KRW')\n";
	$strSql = $strSql."		when coin='BTC' then (SELECT SUM(balance) FROM ex_bank_history WHERE user_id='$user_id' AND assetsType='1' AND flag=0 AND symbol='BTC')\n";
	$strSql = $strSql."		when coin='ETH' then (SELECT SUM(balance) FROM ex_bank_history WHERE user_id='$user_id' AND assetsType='1' AND flag=0 AND symbol='ETH')\n";
	$strSql = $strSql."		when coin='MCC' then (SELECT SUM(balance) FROM ex_bank_history WHERE user_id='$user_id' AND assetsType='1' AND flag=0 AND symbol='MCC')\n";
	$strSql = $strSql."		when coin='GAME' then (SELECT SUM(balance) FROM ex_bank_history WHERE user_id='$user_id' AND assetsType='1' AND flag=0 AND symbol='GAME')\n";
	$strSql = $strSql."		when coin='RTLT' then (SELECT SUM(balance) FROM ex_bank_history WHERE user_id='$user_id' AND assetsType='1' AND flag=0 AND symbol='RTLT')\n";
	$strSql = $strSql."	end as bal_02\n";
	$strSql = $strSql."FROM ex_coin\n";
	$strSql = $strSql."ORDER BY idx ASC\n";

	$results = $database->get_results($strSql);

	echo json_encode($results);
?>