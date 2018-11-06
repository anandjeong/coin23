<?php
 
	require_once $_SERVER['DOCUMENT_ROOT']."/json/init_DB.php"; 
 
	$table = "trading";
	 
	$recDate = gmdate("Y-m-d H:i:s", time()); 
	$params = array(); 
	
	$where['idx'] = @$_POST['idx']; 
	$add_query = $database->delete( $table ,   $where , 1 );
 

echo  @$_POST['idx']; 

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