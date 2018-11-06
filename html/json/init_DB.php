<?

	define( 'DB_HOST', '211.249.153.82' ); // set database host
//	define( 'DB_USER', 'root' ); // set database user
//	define( 'DB_PASS', 'dkffkelsWkd@'); // set database password
//	define( 'DB_NAME', 'PJT_COIN' ); // set database name
	define( 'DB_USER', 'coin23' ); // set database user
	define( 'DB_PASS', 'zhdls23@#'); // set database password
	define( 'DB_NAME', 'COIN23' ); // set database name
	define( 'SEND_ERRORS_TO', 'aladdin@aladdinvm.com' ); //set email notification email address
	define( 'DISPLAY_DEBUG', true ); //display db errors?

	error_reporting(E_ALL); 
	ini_set("display_errors", 1);


	// init
	define('PATH_DIR', dirname(__FILE__)); 
	define('LIB_DIR', PATH_DIR.'/json');  

	require_once (PATH_DIR."/class.db.php"); 

	//Initiate the class
	$database = new DB(); 
 ?>