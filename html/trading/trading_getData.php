<?php
    @session_start(); 
    require_once $_SERVER['DOCUMENT_ROOT']."/json/init_DB.php";

    $TABLE = " trading ";
    $RECORD = array();
    // $userID = @$_POST['userID']?@$_POST['userID']:"tomas";
 
    // $WHERE = " WHERE  auth = '$userID' "; 
 
    $result_array = array(); // 결과 값을 담을 변수 생성 

$query_get_date = " SELECT  trading_date   FROM trading ORDER BY trading_date DESC  limit 1 ";
        list( $trading_date ) = $database->get_row($query_get_date);
     
$trading_date  = substr($trading_date , 0, 10);

    $query = " SELECT (SELECT  trading_money   FROM trading limit 1) as last, MIN(trading_money) as low, MAX(trading_money) as high, AVG(trading_money) as avg,'mcc' as  currency FROM trading
                where coin_name ='mcc' ";//AND  trading_money >= date_sub( $trading_date, interval 1 day)";

    $results = $database->get_results( $query );
 
    foreach( $results as $row )
    {
        $result_array[] = $row;
    }
 
    /* close connection */

    //$database->disconnect();
    echo @$_GET['callback'].json_encode($result_array);
     // /$_GET['callback'].
?>