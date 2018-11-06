<?php
    @session_start(); 
    require_once $_SERVER['DOCUMENT_ROOT']."/json/init_DB.php";

    $TABLE = " ex_coin_account ";
    $RECORD = array();
    $userID = @$_POST['userID']?@$_POST['userID']:"tomas";
 
    $WHERE = " WHERE  auth = '$userID' "; 
 
    $result_array = array(); // 결과 값을 담을 변수 생성 

    $query = "SELECT authkey AS myWallet , point AS MyCash, MCC ,currency FROM $TABLE $WHERE  ";

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