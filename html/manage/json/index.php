<?require_once $_SERVER['DOCUMENT_ROOT']."/json/init_DB.php";?>

<?
	$pageType = $_REQUEST["pageType"];
	switch($pageType){
		case "mList": case "cList" : case "rkList" : case "rcList" : case "trList1" : case "trList2" :
			require_once $_SERVER['DOCUMENT_ROOT']."/manage/json/list.php";
			break;
		case "view":
			break;
		case "write":
			break;
		case "update":
			require_once $_SERVER['DOCUMENT_ROOT']."/manage/json/update.php";
			break;
		case "dataChk":
			require_once $_SERVER['DOCUMENT_ROOT']."/manage/json/dataChk.php";
			break;
	}
?>