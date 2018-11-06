//토큰주소 배열
var cAddr = new Array();

cAddr.MCC = "0x81fe677cb2cb26abf471f40cd8256ce9d07fed3a";
cAddr.GAME = "0xcbdfad49dad16a992978f2767506a0f3f11ae9c7";
cAddr.RTLT = "0xbdffa2acb93dd2a8ae9d2fcc3c2329906efd38da";


//타임스탬프 형식 바꾸기 예) getTIMESTAMP(time)
var getTIMESTAMP = function(time) {
	var date = new Date(time*1000);
	var year = date.getFullYear();
	var month = ("0"+(date.getMonth()+1)).substr(-2);
	var day = ("0"+date.getDate()).substr(-2);
	var hour = ("0"+date.getHours()).substr(-2);
	var minutes = ("0"+date.getMinutes()).substr(-2);
	var seconds = ("0"+date.getSeconds()).substr(-2);

	return year+"-"+month+"-"+day+" "+hour+":"+minutes+":"+seconds;
}

//문자열 자르기 예) str.cutStr(n)
String.prototype.cutStr = function(length){
	return this.substring(0, this.length-length);
}

//문자열 왼쪽부터 자르기 예) str.left(n)
String.prototype.left = function(length){
	if(this.length <= length){
		return this;
	}else{
		return this.substring(0, length);
	}
}

//문자열 오른쪽부터 자르기 예) str.right(n)
String.prototype.right = function(length){
	if(this.length <= length){
		return this;
	}else{
		return this.substring(this.length - length, this.length);
	}
}

//콤마찍기 예) str.comma(n)
String.prototype.comma = function(n){
    var len, point, str; 
       
    num = this + ""; 
    point = num.length % n ;
    len = num.length; 
   
    str = num.substring(0, point); 
    while (point < len) { 
        if (str != "") str += ","; 
        str += num.substring(point, point + n); 
        point += n; 
    } 
    
    return str;
 }

//알파벳 대문자로 변경
//toUpperCase()

//알파벳 소문자로 변경
//toLowerCase()
