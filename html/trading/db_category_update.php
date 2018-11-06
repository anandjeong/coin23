<?php

	require_once $_SERVER['DOCUMENT_ROOT']."/lib/init_DB.php";

    $table  = "";

	$params = array();
	$state =  strtoupper(trim(@$_POST['state']));

    $_ordinary  = strtoupper(trim(@$_POST['ordinary']));
    $_label  = strtoupper(trim(@$_POST['label']));


    switch ( $state) {
        case 'O':
        case 'L':  $table  = " mall_cate_step1 ";

            $params['ordinary']     =    $ordinary;
            $params['menu_label']   = $label;
           break;

        case 'O2':
        case 'L2':  $table  = " mall_cate_step2 ";

            $params['ordinary']     =    $ordinary;
            $params['menu_label']   = $label;

        break;

        case 'O3':
        case 'L3':  $table  = " mall_cate_step3 ";

            $params['ordinary']     =    $ordinary;
            $params['menu_label']   = $label;
        break;


        case 'U': $table  = " mall_cate_step1 ";
            $params['used']     =    @$_POST['value'];

        case 'U2': $table  = " mall_cate_step2 ";
            $params['used']     =     @$_POST['value'];

        case 'U3': $table  = " mall_cate_step3 ";
            $params['used']     =      @$_POST['value'];


        case 'M': $table  = " mall_cate_step1 ";
            $params['menu_display']     =    @$_POST['value'];

        case 'M2': $table  = " mall_cate_step2 ";
            $params['menu_display']     =     @$_POST['value'];

        case 'M3': $table  = " mall_cate_step3 ";
            $params['menu_display']     =      @$_POST['value'];



        case 'C': $table  = " mall_cate_step1 ";
            $params['category_display']     =    @$_POST['value'];

        case 'C2': $table  = " mall_cate_step2 ";
            $params['category_display']     =     @$_POST['value'];

        case 'C3': $table  = " mall_cate_step3 ";
            $params['category_display']     =      @$_POST['value'];


        // default:         //     # code...        //     break;
    }



	$where['idx'] =@$_POST['idx'];

	$add_query = $database->update( $table , $params, $where , 1 );

	echo $state;//$add_query;

?>