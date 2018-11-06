<?php
    @session_start(); 
    require_once $_SERVER['DOCUMENT_ROOT']."/json/init_DB.php";

    $TABLE = " trading  ";
    $RECORD = array();
    $WHERE = "";
    
    // if(@$_POST['dealstate'] == 1)
    //     $WHERE = " WHERE  trade_state = '1' order by idx, account_date   LIMIT  1"; 
    // else
    //     $WHERE = " WHERE  dealState = '0' order by idx desc  $LIMIT "; 

    $WHERE = " WHERE  trade_state = '1' order by idx desc"; 

    $result_array = array(); // 결과 값을 담을 변수 생성 

    $query = "SELECT * FROM $TABLE $WHERE  ";

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