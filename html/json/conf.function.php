<?
//테그설정
function setNumber($str){
	$str = str_replace(",", "", trim($str));
	return $str;
}

function str_json2($str) {
   $str = str_replace("\\", "\\\\", $str);
   $str = str_replace("\'", "\\\'", $str);
   $str = str_replace("'", "\\'", $str);
   $str = str_replace("\"", "\\\"", $str);
   $str = str_replace("\r\n", "\\n", $str);
   $str = str_replace("\n", "\\n", $str);
   return $str;
}

function str_json($str) {
	 $str = str_replace("'", "‘", $str);
	 $str = str_replace("&", "", $str);
	 $str = str_replace("\r\n", "", $str);
	 $str = str_replace("\r", "", $str);
	 $str = str_replace("\n", "", $str);
	 $str = str_replace("\t", "", $str);
	 $str = str_replace("\\", "", $str);
   $str = str_replace("\'", "‘", $str);
   $str = str_replace("\"", "‘", $str);
   //$str = str_replace("<", "&lt;", $str);
   //$str = str_replace(">", "&gt;", $str);
   return $str;
}

function str_json_content($str) {
   $str = str_replace("\\", "\\\\", $str);
   $str = str_replace("\'", "\\\'", $str);
   $str = str_replace("'", "\\'", $str);
   $str = str_replace("\"", "\\\"", $str);
   $str = str_replace("\r\n", "\\n", $str);
   $str = str_replace("\n", "\\n", $str);
   //$str = str_replace("<", "&lt;", $str);
   //$str = str_replace(">", "&gt;", $str);
   return $str;
}

function str_result($str)
{
	$result = str_replace("'", "\\'", $str);
        $result = str_replace("\n", "", $result);
        $result = str_replace("\"", "", $result);
        $result = str_replace("<", "(", $result);
        $result = str_replace(">", ")", $result);
	return $result;
}

function safe_sql($str) 
{
	$str = trim($str);
	$str = htmlspecialchars($str);
  $res = eregi_replace("(select|union|insert|update|delete|drop|\"|\'|#|\/\*|\*\/|\\\|\;)","", $str);
  return $res;
}

function create_auth_key($str)
{
	$user_ip = $_SERVER['REMOTE_ADDR'];
	$auth_key = md5($user_ip.$str);
	return $auth_key;
}

function check_auth_key($key,$str)
{
	$user_ip = $_SERVER['REMOTE_ADDR'];
	$ck_str = md5($user_ip.$str);
	if(!$_SESSION["_auth_key"])
		return "ERR-KEY";
	elseif($_SESSION["_auth_key"] != $ck_str)
		return "ERR-UNLIKE";
	elseif($_SESSION["_auth_key"] != $key)
		return "ERR-SESSION";
	else return true;
}

//오류메시지 출력및 페이지이동
function show_msg($str, $url= "") 
{
    if ($url == "") $url = "history.go(-1)";
    elseif ($url == "close") $url = "window.close()";
    else $url = "document.location.href = '$url'";

    if ($str != "") echo "<script language='javascript'>alert('$str');$url;</script>"; 
    else echo "<script language='javascript'>$url;</script>"; 
    exit;
}

function move_msg($str, $url= "") 
{
    if ($url == "") $url = "history.go(-1)";
    elseif ($url == "close") $url = "window.close()";
    else $url = "top.location.replace('$url')";

    if ($str != "") echo "<script language='javascript'>alert('$str');$url;</script>"; 
    else echo "<script language='javascript'>$url;</script>"; 
    exit;
}

//패킷전송코드생성
function make_code($code)
{
        $code_len = strlen($code);
        for($i=0;$i<10-strlen($code_len);$i++)
        {
                $len_field .= "0";
        }

        return $len_field.$code_len.$code;
}

//패킷전송
function send_code($code,$server,$port)
{
        $fp = fsockopen ($server,$port, $errno, $errstr, 30);
        if (!$fp)
        {
                echo "$errstr ($errno) <br>\n";
                return 0;
        }
        else
        {
                fputs ($fp, $code);
                $message="";
                $resultVal = "";
                $totmsg = "";
                while (!feof($fp))
                {
                        $lenstr = fread ($fp,10);
                        //echo "payloadlen: ".$lenstr."<br>\n";
			if(!$lenstr) break;
                        $message="";
                        $message = fread ($fp,$lenstr);
                        //$totmsg .= $message;
                        //echo $message."<br>\n";

                        $sublen = strlen($message);
                        $resultVal =  substr ($message, 0, 4);

                        if($message[$sublen-1] == "\n")
                        {
                                //echo "<br><br> --continue";
                                //sleep(1);
                                $totmsg .= substr($message, 4, $sublen-5);
                                continue;
                        }
                        else
                        {
                                $totmsg .= substr($message, 4, $sublen-4);
                                break;
                        }
                }
                fclose($fp);
                return $resultVal.$totmsg;
        }
}

//<select 옵션리스트
function set_select_option($list, $val) {
   foreach ($list as $key => $value) {
      $selected = (($val != '') && ($key == $val)) ? ' selected' : '';
      $option .= '<option value=\''.$key.'\''.$selected.'>'.$value.'</option>';
   }

   return $option;
}

//발신금지 시간설정-현재사용안함
function frobiden($str,$ftime,$ttime)
{
	if(!$str || $str == "----------" || $str == "NNNNN")
	{
		$result = "제한없음";
	}
	if(substr($str,0,1) == "Y")
	{
		$result = "전체제한";
	}
	else
	{
		if(substr($str,1,1)=="Y")
		{
			$result = "시내/";
		}
		if(substr($str,2,1)=="Y")
		{
			$result .= "시외/";
		}
		if(substr($str,3,1)=="Y")
		{
			$result .= "휴대/";
		}
		if(substr($str,4,1)=="Y")
		{
			$result .= "국제";
		}
		
	}
	if(!$ftime || !$ttime || $ftime == "0" || $ttime == "0")
	{
		return $result;
	}
	else
	{
		$result .= "(".$ftime.":00~".$ttime.":00)";
	}
	return $result;
}

function search_arr($arr,$field, $search)
{
	foreach($arr as $key => $v) 
	{
    		foreach($arr[$key] as $k =>$v) 
    		{
    			if($k == $field && $v == $search)
    			{
    				$records[] = $arr[$key];
    			}
    		}
	}
	return $records;
}

function strpos_arr($arr,$field, $search)
{
        foreach($arr as $key => $v)
        {
                foreach($arr[$key] as $k =>$v)
                {
			$pos = strpos($v,$search);
                        if($k == $field && $pos !== false)
                        {
                                $records[] = $arr[$key];
                        }
                }
        }
        return $records;
}

function make_mktime($str)
{
	if($str == "0")
	{
		return 0;
	}
	if(!strpos($str,"-"))
	{
		$yy = substr($str,0,4);
		$mm = substr($str,4,2);
		$dd = substr($str,6,2);
	}
	else
	{
		$tmp = explode("-",$str);
		$yy = $tmp[0];
		$mm = $tmp[1];
		$dd = $tmp[2];
	}
	return $yy.$mm.$dd;
	//return mktime(0,0,0,$mm,$dd,$yy);
}

function change_date($str)
{
	if(substr($str,0,4) == "1970")
		$res = 0;
	else 
		$res = substr($str,0,4)."-".substr($str,4,2)."-".substr($str,6,2);
	return $res;
}


function str_array($strsrc) // 문자열을 분리(한글도 한글자)해서 배열에 저장하는 함수 
{ 
	$length=strlen($strsrc); 
	$han=0; 
	$total_length=0; 
	
	for($i=0;$i<$length;$i++) 
	{ 
		$word=substr($strsrc,$i,1); 
		if(ord($word)>127) 
		{ 
			$han+=1; 
			if($han==2) 
			{ 
				$str[$total_length]=substr($strsrc,$i-1,2); 
				$total_length++; 
				$han=0; 
			} 
		} 
		else 
		{ 
			$str[$total_length]=$word; 
			$total_length++; 
		} 
	} 
	
	return $str; 
} 

function hsubstr($strsrc, $start, $end="") 
{ 
	$string=str_array($strsrc); 
	$size=sizeof($string); 
	if($start<0) 
	{ 
		$start=$size+$start; 
	} 
	
	if($end<0) 
	{ 
		$end=$size+$end-1; 
	} 
	elseif(!$end) 
	{ 
		$end=$size-1; 
	} 
	else 
	{ 
		$end=$start+$end-1; 
	} 
	
	for($first=$start;$first<=$end;$first++) 
	{ 
		$result.=$string[$first]; 
	} 
	
	return $result; 
}

function auto_link($contents) 
{
	$pattern = "/(http|https|ftp|mms):\/\/[0-9a-z-]+(\.[_0-9a-z-]+)+(:[0-9]{2,4})?\/?";       // domain+port
	$pattern .= "([\.~_0-9a-z-]+\/?)*";                         // sub roots
	$pattern .= "(\S+\.[_0-9a-z]+)?";                                                    // file & extension string
	$pattern .= "(\?[_0-9a-z#%&=\-\+]+)*/i";                                         // parameters
	$replacement = "<a href=\"\\0\" target=\"_blank\">\\0</a>";
	return preg_replace($pattern, $replacement, $contents, -1);
}

function get_weeknum($get_year, $get_month, $get_day)
{
	//같은 요일의 시작날까지의 주차를 구한다.
	//주의 차이이므로 1을 더한다.(이번이 3번째주면 1째주랑 2주의 차이가 난다.)
	$week_num = floor($get_day/7) +1;
  //같은 요일의 시작날짜를 구한다.
  $start_week_day = $get_day%7;
  //시작 날짜의 요일값(일요일:0, 토요일:6)을 구한다.
  $dateno=date('w', mktime(0, 0, 0, $get_month, $get_day, $get_year));
  //요일값이 0부터 시작하므로 1씩 더해서 1부터 시작하도록 마춰주고,
  $dateno++;
  //요일값이 실제 날짜보다 작으면 지난주에 이번 달이 시작된거이므로 주차값 증가
  if($start_week_day>$dateno)
  {
  	$week_num++;
	}
	return $week_num;
}

/*줄여줄 이미지의 가로세로 크기*/ /*생성될 이미지파일이름*/ /*원본 파일이름*/
function GD2_make_thumb($max_x,$max_y,$dst_name,$src_file) 
{
	$img_info=@getimagesize($src_file);
	$sx = $img_info[0];
	$sy = $img_info[1];
	//썸네일 보다 큰가?
	if ($sx>$max_x || $sy>$max_y) 
	{
		if ($sx>$sy) 
		{
			$thumb_y=ceil(($sy*$max_x)/$sx);
			$thumb_x=$max_x;
		} 
		else 
		{
			$thumb_x=ceil(($sx*$max_y)/$sy);
			$thumb_y=$max_y;
		}
	} 
	else 
	{
		$thumb_y=$sy;
		$thumb_x=$sx;
	}
	// JPG 파일인가?
	if ($img_info[2]=="2") 
	{
		$_dq_tempFile=basename($src_file);                                //파일명 추출
		$_dq_tempDir=str_replace($_dq_tempFile,"",$src_file);        //경로 추출
		$_dq_tempFile=$_dq_tempDir.$dst_name.$_dq_tempFile;        //경로 + 새 파일명 생성
		
		echo "_dq_tempFile".$_dq_tempFile."<BR>";
		echo "_dq_tempDir".$_dq_tempDir."<BR>";
		echo "_dq_tempFile".$_dq_tempFile."<BR>";
		
		$_create_thumb_file = true;
		if (file_exists($_dq_tempFile)) 
		{ //섬네일 파일이 이미 존제한다면 이미지의 사이즈 비교
			$old_img=@getimagesize($_dq_tempFile);
			if($old_img[0] != $thumb_x) $_create_thumb_file = true; else $_create_thumb_file = false;
			if($old_img[1] != $thumb_y) $_create_thumb_file = true; else $_create_thumb_file = false;
		}
		if ($_create_thumb_file) 
		{
			// 복제
			$src_img=ImageCreateFromjpeg($src_file);
			$dst_img=ImageCreateTrueColor($thumb_x, $thumb_y);
			
			echo "src_img".$src_img."<BR>";
			echo "dst_img".$dst_img."<BR>";
			
			ImageCopyResampled($dst_img,$src_img,0,0,0,0,$thumb_x,$thumb_y,$sx,$sy);
			Imagejpeg($dst_img,$_dq_tempFile,100);
			// 메모리 초기화
			ImageDestroy($dst_img);
			ImageDestroy($src_img);
		}
	}
}

function sendMail($EMAIL, $NAME, $SUBJECT, $CONTENT, $MAILTO, $MAILTONAME){
    $mail             = new PHPMailer();
    $body             = $CONTENT;
 
    $mail->IsSMTP(); // telling the class to use SMTP
    $mail->Host       = "www.coolio.so"; // SMTP server
    $mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
                                               // 1 = errors and messages
                                               // 2 = messages only
    $mail->CharSet    = "utf-8";
    $mail->SMTPAuth   = true;                  // enable SMTP authentication
    $mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
    $mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
    $mail->Port       = 465;                   // set the SMTP port for the GMAIL server
    $mail->Username   = "사용자 계정";             // GMAIL username
    $mail->Password   = "비밀번호";              // GMAIL password
 
    $mail->SetFrom($EMAIL, $NAME);
 
    $mail->AddReplyTo($EMAIL, $NAME);
 
    $mail->Subject    = $SUBJECT;
 
    $mail->MsgHTML($body);
 
    $address = $MAILTO;
    $mail->AddAddress($address, $MAILTONAME);
 
    if(!$mail->Send()) {
      echo "Mailer Error: " . $mail->ErrorInfo;
    } else {
      echo "Message sent!";
    }
}

function send_email($from,$to,$title,$body)
{
	$mailheaders .= "Return-Path: $from\r\n";
  $mailheaders .= "From: <$from>\r\n";
  $mailheaders .= "X-Mailer: webmail\r\n";
  $boundary = "----".uniqid("part");
  // 헤더 파트를 작성한다.
  // Multipart/mixed 일경우 첨부파일이 있다는 것을 의미한다.
  $mailheaders .= "MIME-Version: 1.0\r\n";
  $mailheaders .= "Content-Type: Multipart/mixed; boundary=\"$boundary\"";
  // 본문 파트를 작성한다.
  $bodytext = "This is a multi-part message in MIME format.\r\n\r\n";
  $bodytext .= "--$boundary\r\n";
  $bodytext .= "Content-Type: text/html; charset=\"utf-8\"\r\n";
  $bodytext .= "Content-Transfer-Encoding: base64\r\n\r\n";
  $bodytext .= chunk_split(base64_encode($body))."\r\n\r\n";
  // 멀티파트 종료를 작성한다.
  $bodytext .= "--$boundary--";
  
  $res = mail($to, $title, $bodytext, $mailheaders);
  backup_save_log( "1 $res <BR>");
  
	if( $res )
		return TRUE;
	else
		return FALSE;
}

function send_email_socks($from,$to,$subject,$body)
{
	//$from = 'heaven@lgdacom.net';
	$from_arr = explode("@",$from);
	$fromdomain = $from_arr[1];	//'quickfile.co.kr';
	$fromname = $from_arr[0];	//'배성진';
	//$to = 'koreanrzro@hanmail.net';
	$to_arr = explode("@",$to);
	$todomain = $to_arr[1];	//'hanmail.net';
	$toname = $to_arr[0];	//'배성진';
	//$subject = '소켓메일 테스트 입니다.';
	
	$subject = iconv("utf-8","euc-kr",$subject);
	if($todomain == "nate.com")
		$charset = "korean";
	else if($todomain == "gmail.com" || $todomain == "gmail.co.kr"){
		$from = "<$from>";
		$to = "<$to>";	
	} else
		$charset = "ks_c_5601-1987";
	
	$charset = "utf-8";
	
	$data = "To: $toname <$to>\r\nFrom: $fromname <$from>\r\nSubject: $subject\r\nContent-Type: text/html; charset=$charset\r\nContent-Transfer-Encoding: base64\r\n\r\n".chunk_split(base64_encode($body))."\r\n.\r\n";
	
	$errno = 0;
	$errstr = 0;
	
	//echo "Connect Start...<BR>";
	
	if(false === getmxrr($todomain, $mxhosts)) 
	{
		//exit("no mx record\n");
		return "no mx record";
		backup_save_log("no mx record");
	}
	
	$rows = count($mxhosts);
	$fastmx = 0;
	$oldtime = 999;
	for($i = 0; $i < $rows; $i ++) 
	{
		$starttime = explode(' ', microtime());
		if(false === ($socks[$i] = fsockopen($mxhosts[$i], 25, $errno, $errstr, 30))) 
		{
			//echo $mxhosts[$i]." not connected <BR>";
			continue;
		}
		else
			$sock_close[] = $i;
		$endtime = explode(' ', microtime());
		$thistime[0] = $endtime[0] - $starttime[0];
		$thistime[1] = $endtime[1] - $starttime[1];
		$ttime = $thistime[0] + $thistime[1];
		//echo $mxhosts[$i]." $ttime <BR>";
		if($ttime < $oldtime) 
		{
			$oldtime = $ttime;
			$fastmx = $i;
		}
	}
	
	$sock = &$socks[$fastmx];
	
	//echo $mxhosts[$fastmx]." Connected errno:$errno errstr:$errstr <BR>";
	
	$response = fread($sock, 256);
	$bytes_left = socket_get_status($sock);
	
	//if($bytes_left > 0)	$response .= fread($sock, $bytes_left['unread_bytes']);
	//echo "1 $response <BR>";
	if(substr($response, 0, 3) != '220')
	{
		//exit("Connection failure\n");
		return "[220]Connection failure ".$response;
		backup_save_log("[220]Connection failure ".$response);
	}
	
	fputs($sock, "EHLO $fromdomain\r\n");
	$response = fread($sock, 256);
	$bytes_left = socket_get_status($sock);
	//if($bytes_left > 0)	$response .= fread($sock, $bytes_left['unread_bytes']);
	//echo "2 $response	<br>";
	if(substr($response, 0, 3) != '250')
	{
		//exit("EHLO failure\n");
		return "[250]EHLO failure ".$response;
		backup_save_log("[250]EHLO failure ".$response);
	}

	fputs($sock, "MAIL FROM: $from\n");
	$response = fread($sock, 256);
	$bytes_left = socket_get_status($sock);
	//if($bytes_left > 0)	$response .= fread($sock, $bytes_left['unread_bytes']);
	//echo "3 $response <br>";
	if(substr($response, 0, 3) != '250')	
	{
		//exit("MAIL FROM failure\n");
		return "[250]MAIL FROM failure ".$response;
		backup_save_log("[250]MAIL FROM failure ".$response);
	}
	
	fputs($sock, "RCPT TO: $to\r\n");
	$response = fread($sock, 256);
	$bytes_left = socket_get_status($sock);
	//if($bytes_left > 0)	$response .= fread($sock, $bytes_left['unread_bytes']);
	//echo "4 $response <br>";
	if(substr($response, 0, 3) != '250')
	{
		//exit("RCPT TO failure\n");
		return "[250]RCPT TO failure ".$response;
		backup_save_log("[250]RCPT TO failure ".$response);
	}
	fputs($sock, "DATA\r\n");
	$response = fread($sock, 256);
	$bytes_left = socket_get_status($sock);
	//if($bytes_left > 0)	$response .= fread($sock, $bytes_left['unread_bytes']);
	//echo "5 $response <br>";
	if(substr($response, 0, 3) != '354')
	{
		//exit("DATA failure\n");
		return "[354]DATA failure ".$response;
		backup_save_log("[354]DATA failure ".$response);
	}
	fputs($sock, $data);
	$response = fread($sock, 256);
	$bytes_left = socket_get_status($sock);
	//if($bytes_left > 0)	$response .= fread($sock, $bytes_left['unread_bytes']);
	//echo "6 $response <br>";
	if(substr($response, 0, 3) != '250')
	{
		//exit("MESSAGES failure\n");
		return "[250]MESSAGES failure ".$response;
		backup_save_log("[250]MESSAGES failure ".$response);
	}
	fputs($sock, "QUIT\r\n");
	$response = fread($sock, 256);
	$bytes_left = socket_get_status($sock);
	//if($bytes_left > 0)	$response .= fread($sock, $bytes_left['unread_bytes']);
	//echo "7 $response <br>";
	if(substr($response, 0, 3) != '221')
	{
		//exit("QUIT failure\n");
		return "[221]QUIT failure ".$response;
		backup_save_log("[221]QUIT failure ".$response);
	}
	
	$rows = count($sock_close);
	for($i = 0; $i < $rows; $i ++)
	fclose($socks[$sock_close[$i]]);
	
	return "SUCCESS";
}


function send_email_socks_smtp($fromname,$from,$toname,$to,$subject,$body)
{
		 $charset = "UTF-8";
     $smtp_server   = SMTP_SERVER;	//"smtp.mailwood.com";//"sfilter.mailwood.com";	//"211.40.221.190";	// //enter your smtp server here
     $smtp_user      = SMTP_USER;	//"sjbae@lgdacom.biz";    //enter your smtp username here 
     $smtp_passwd = SMTP_PASS;	//"";
		 $smtp_port = SMTP_PORT;
		 
     if (!$smtp_sock = fsockopen("$smtp_server", $smtp_port, $errno, $errstr, 30)) {
          die ("Couldn't open mail connection to $smtp_server! $errstr\n");
          backup_save_log( "Couldn't open mail connection to $smtp_server! $errstr\n");
     }

     fputs($smtp_sock, "AUTH LOGIN\n");
     fputs($smtp_sock, base64_encode($smtp_user)."\n");
     fputs($smtp_sock, base64_encode($smtp_passwd)."\n");
     $response = fread($smtp_sock, 256);
		 backup_save_log( "1 $response <BR>");
		 
     fputs($smtp_sock, "HELO $smtp_server\n");
     $response = fread($smtp_sock, 256);
		 backup_save_log( "2 $response <BR>");
		 
     fputs($smtp_sock, "VRFY $smtp_user\n");
     $response = fread($smtp_sock, 256);
		 backup_save_log( "3 $response <BR>");
     fputs($smtp_sock, "MAIL FROM:$from\n");
     $response = fread($smtp_sock, 256);
		 backup_save_log( "4 $response <BR>");
     fputs($smtp_sock, "RCPT TO:$to\n");
     $response = fread($smtp_sock, 256);
		 backup_save_log( "5 $response <BR>");
     fputs($smtp_sock, "DATA\n");
     $response = fread($smtp_sock, 256);
		 backup_save_log( "6 $response <BR>");
		 
		 if(preg_match('/(daum.net|hanmail.net)/i', $to)){
			 $subject = iconv("UTF-8","EUC-KR",$subject);
		 }
		 $data = "To: $toname <$to>\r\nFrom: $fromname <$from>\r\nSubject: $subject\r\nContent-Type: text/html; charset=$charset\r\nContent-Transfer-Encoding: base64\r\n\r\n".chunk_split(base64_encode($body))."\r\n.\r\n";
		 fputs($smtp_sock, $data);
		 
		 $response = fread($smtp_sock, 256);
		 backup_save_log( "7 $response <BR>");
     fputs($smtp_sock, "QUIT\r\n");
     
     $response = fread($smtp_sock, 256);
		 backup_save_log( "0 $response <BR>");
		 
     fclose($smtp_sock);
	
	return "SUCCESS";
}


function rand_str($no)
{
	srand ((float) microtime() * 10000000); 
	$input = array ("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "1", "2", "3", "4", "5", "6", "7", "8", "9");        
	// 출력할 값을 입력 
	$input2 = array_rand( array_flip( $input ) , $no ); 
	$strRand = implode( "" , $input2 ); 

	return $strRand;
}

function rand_num($no)
{
	srand ((float) microtime() * 10000000); 
	$input = array ("1", "2", "3", "4", "5", "6", "7", "8", "9", "0");
	// 출력할 값을 입력 
	$input2 = array_rand( array_flip( $input ) , $no ); 
	$strRand = implode( "" , $input2 ); 

	return $strRand;
}

function rand_alpa($no)
{
	srand ((float) microtime() * 10000000); 
	$input = array ("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");        
	$input2 = array_rand( array_flip( $input ) , $no );
	if($no > 1) $strRand = implode( "" , $input2 );
	else $strRand = $input2;
	return $strRand;
}

function width_check($str)
{
	
	//$pos = strpos($ckstr, $str);
	$res = str_replace("WIDTH: 720px;","WIDTH: 690px;",$str);
	$res = str_replace("WIDTH: 700px;","WIDTH: 690px;",$res);
	return $res;
}

function substrKR($strSource,$iStart,$iLength,$iCuttingType = false) { 
  $iRealStart = 0; 
  $iRealLength = 0; 
  $iSourceLength = strlen($strSource); 
  $iCountHangul = 0; 
  $iCuttingType = ($iCuttingType == 0) ? -1 : 1; 

  // 인자 무결성 검사 
  if (($iLength <= 0) || ($iStart < 0) || ($iStart > $iSourceLength)) return ""; 

  // 시작 포인트 계산 
  for($i=0; $i<$iSourceLength; $i++) { 
          if ($i == $iStart) { 
                  $iRealStart = (($iCountHangul % 2)==0) ? $i : $i + $iCuttingType; 
                  break; 
          } 
          if (ord($strSource[$i]) & 0x80) 
                  $iCountHangul += 1; 
  } 
  // 잘라낼 문자열 길이 계산 
  $iCountHangul = 0; 
  for($i=$iRealStart, $j=1; $i<$iSourceLength; $i++, $j++) { 
          if (ord($strSource[$i]) & 0x80) 
                  $iCountHangul += 1; 
          if ($j >= $iLength) { 
                  $iRealLength = (($iCountHangul % 2)==0) ? $j : $j + $iCuttingType; 
                  break; 
          } 
  } 
  // 남은 문자열의 길이가 지정한 길이보다 작다면 길이 값 그대로 반환 
  if ($i==$iSourceLength) $iRealLength = $iLength; 

  return substr($strSource,$iRealStart,$iRealLength); 
} 

function getSearchNum($str)
{
	$tmp = str_replace("   "," ",trim($str));
	$res = str_replace(" ",",",str_replace("  "," ",$tmp));
	//$res = explode(",",$str);
	return $res;
}

function backup_save_log($sql) {
	$tmp = explode("/", trim( $_SERVER['DOCUMENT_ROOT'] ) );
	$host="";
	for($i=0; $i<count($tmp)-1; $i++){
		if(strlen($tmp[$i]) >0){
			$host.="/";
			$host.= $tmp[$i];
		}
	}

	$savepatten=array("/\n/","/^M/");
	$savereplace=array("","\\r\\n");
//	$savetemp = "[".date("H").":".date("i")."][".$_SERVER['REMOTE_ADDR']."]".$_SERVER['SCRIPT_FILENAME']."[".$_SESSION['_ADMIN_ID']."]".preg_replace($savepatten,$savereplace,$sql)."\n";
	$savetemp = "[".date("H").":".date("i")."][".$_SERVER['REMOTE_ADDR']."]".$_SERVER['SCRIPT_FILENAME'].preg_replace($savepatten,$savereplace,$sql)."\n";
	$file = $host."/data/log/".date("Y")."_".date("m")."_".date("d")."_"."00______";
	$fp = fopen("$file","a");
	fputs($fp,$savetemp);
	fclose($fp);
}

function Encrypt($str, $secret_key='secret key', $secret_iv='secret iv')
{
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 32)    ;

    return str_replace("=", "", base64_encode(
			openssl_encrypt($str, "AES-256-CBC", $key, 0, $iv))
    );
}

function Decrypt($str, $secret_key='secret key', $secret_iv='secret iv')
{
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 32);

    return openssl_decrypt(
			base64_decode($str), "AES-256-CBC", $key, 0, $iv
    );
}

function stringEncryption($action, $string){
  $output = false;

  $encrypt_method = 'AES-256-CBC';	// Default
  $secret_key = 'qotjdwls@eoqkr';		// Change the key!
  $secret_iv = '#@$%^&*()_+=-';			// Change the init vector!

  // hash
  $key = hash('sha256', $secret_key);

  // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
  $iv = substr(hash('sha256', $secret_iv), 0, 16);

  if( $action == 'encrypt' ) {
      $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
      $output = base64_encode($output);
  } else if( $action == 'decrypt' ){
      $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
  }

  return $output;
}


function SendSocketPost($host,$path,$query,$port=80) {
	$fp = @fsockopen($host, $port, $errno, $errstr, 3);
	if(!$fp) {
		@fclose($fp);
		return "ERROR : $errstr ($errno)";
	} else {
		$cmd = "POST $path HTTP/1.1\r\n";
		fputs($fp, $cmd);
		$cmd = "Host: $host\r\n";
		fputs($fp, $cmd);
		$cmd = "Content-type: application/x-www-form-urlencoded\r\n";
		fputs($fp, $cmd);
		$cmd = "Content-length: " . strlen($query) . "\r\n";
		fputs($fp, $cmd);
		$cmd = "Connection: close\r\n\r\n";
		fputs($fp, $cmd);
		fputs($fp, $query);
		while($currentHeader = fgets($fp,4096)) {
			if($currentHeader == "\r\n") {
				break;
			}
		}
		$strLine = "";
		while(!feof($fp)) {
			$strLine .= fgets($fp, 4096);
		}
		fclose($fp);
		//($host."|".$path."|".$query."|".$strLine);
		return $strLine;
	}
}

function get_web_page( $url )
{
    $options = array(
        CURLOPT_RETURNTRANSFER => true,     // return web page
        CURLOPT_HEADER         => false,    // don't return headers
        CURLOPT_FOLLOWLOCATION => true,     // follow redirects
        CURLOPT_ENCODING       => "",       // handle all encodings
        CURLOPT_USERAGENT      => "spider", // who am i
        CURLOPT_AUTOREFERER    => true,     // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
        CURLOPT_TIMEOUT        => 120,      // timeout on response
        CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
        CURLOPT_SSL_VERIFYPEER => false
    );

    $ch      = curl_init( $url );
    curl_setopt_array( $ch, $options );
    $content = curl_exec( $ch );
    $err     = curl_errno( $ch );
    $errmsg  = curl_error( $ch );
    $header  = curl_getinfo( $ch );
    curl_close( $ch );

    $header['errno']   = $err;
    $header['errmsg']  = $errmsg;
    $header['content'] = $content;
    return $header['content'];
}

function getExchangePrice( $price, $symbol="KRW" ){
	$sql ="SELECT * FROM coin.ex_coin_price WHERE symbol='KRW' ORDER BY uid DESC LIMIT 1";
	$res = mysql_query($sql);
	$row = mysql_fetch_object($res);
	$_KRW=$row->price;
	
	$sql ="SELECT * FROM coin.ex_coin_price WHERE symbol='".$symbol."' ORDER BY uid DESC LIMIT 1";
	$res = mysql_query($sql);
	$row = mysql_fetch_object($res);
	$_USD=$row->price;
	
	backup_save_log($symbol." ".$_USD);
	$newprice = $price / $_KRW * $_USD;
	return $newprice;
}

function GetValue($str , $name) //해당 함수에서 에러 발생 시 $len => (int)$len 로 수정 후 사용하시기 바랍니다.
{
    $pos1 = 0;  //length의 시작 위치
    $pos2 = 0;  //:의 위치

    while( $pos1 <= strlen($str) )
    {
        $pos2 = strpos( $str , ":" , $pos1);
        $len = substr($str , $pos1 , $pos2 - $pos1);
        $key = substr($str , $pos2 + 1 , $len);
        $pos1 = $pos2 + $len + 1;
        if( $key == $name )
        {
            $pos2 = strpos( $str , ":" , $pos1);
            $len = substr($str , $pos1 , $pos2 - $pos1);
            $value = substr($str , $pos2 + 1 , $len);
            return $value;
        }
        else
        {
            // 다르면 스킵한다.
            $pos2 = strpos( $str , ":" , $pos1);
            $len = substr($str , $pos1 , $pos2 - $pos1);
            $pos1 = $pos2 + $len + 1;
        }            
    }
}

function SendSMS($r,$s,$m,$k){
	$strSql = "INSERT INTO SC_TRAN (TR_SENDDATE, TR_SENDSTAT, TR_MSGTYPE, TR_PHONE, TR_CALLBACK, TR_MSG) VALUES (NOW(), '0', '0', '$r', '025357788', '$m');";
	mysql_query($strSql);
	return "SUC";
	exit;
//	$host = "203.235.28.200";
	$host = "211.249.153.110";

	$path = "/SendSms.php";
	$auth = $_SESSION['_ADMIN_ID'];
	$token= md5($auth.$k.$r.$s);
	$query = "tkn=$token&auth=$auth&authkey=$k&phone=$r&sender=$s&msg=$m";
	$res = SendSocketPost($host,$path,$query,$port=80);
	
	//$url = "http://203.235.28.200/smstest.php?".$query;
	//$res = get_web_page($url);
	backup_save_log($res);

	return $res;
	
	/*
	$fp = @fsockopen($host, "80", $errno, $errstr, 3);
	if(!$fp) {
		@fclose($fp);
		return "ERROR : $errstr ($errno)";
	} else {
		$cmd = "POST $path HTTP/1.0\n";
		fputs($fp, $cmd);
		$cmd = "Host: $host\n";
		fputs($fp, $cmd);
		$cmd = "Content-type: application/x-www-form-urlencoded\n";
		fputs($fp, $cmd);
		$cmd = "Content-length: " . strlen($query) . "\n";
		fputs($fp, $cmd);
		$cmd = "Connection: close\n\n";
		fputs($fp, $cmd);
		fputs($fp, $query);
		while($currentHeader = fgets($fp,4096)) {
			if($currentHeader == "\r\n") {
				break;
			}
		}
		$strLine = "";
		while(!feof($fp)) {
			$strLine .= fgets($fp, 4096);
		}
		fclose($fp);
		//($host."|".$path."|".$query."|".$strLine);
		return $strLine;
	}*/
}
?>
