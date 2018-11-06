$(document).ready(function(){
//	console.log("쿠키 : "+$.cookie("userid"));
	uCnt = $(location).attr('href').split("/").length;
	var cUrl = "";
	for(i=0; i<$(location).attr('href').split("/").length-3; i++){
		if(i==0){
			pageType = $(location).attr('href').split("/")[i+3];
		}
		cUrl = cUrl+"/"+$(location).attr('href').split("/")[i+3];
	}

	pageLoad();

	$(document).on("click", ".divBtn, .menuBtn, .divMenu", function(){
		var st = true;
		if($(this).attr("cKind")=="requestConfirm"){
			if(confirm("입출금요청 처리를 정말 하시겠습니까?")==false){
				st = false;
			}
		}
		if(st==true){
//			console.log(st);
			clickEvent($(this));
		}

	});
});


var clickEvent = function(obj){
	cKind = obj.attr("cKind");

	var params = {}
	params.cKind=cKind;
	
	switch(cKind){
		case "home":
			pageMove("/");
			break;
		case "requestConfirm":
			idx = obj.parent().parent().attr("cIdx");
			params.pageType="update";
			params.idx=idx;
			dataUpdate(params);
			break;
		case "coinUpdate":
			params.symbol=obj.parent().parent().find("td:nth-child(2)").text();
			console.log(params);
			break;
		case "login":
			params.pageType="dataChk";
			params.id=$("input[name=id]").val();
			params.pwd=$("input[name=pwd]").val();
			loginChk(params);
			break;
		case "logout":
			cookieClear();
			location.reload();
			break;
		case "pageMove":
			pageMove("/manage/?pType="+obj.attr("cType"));
			break;
		case "tranHistory":
			tranHistory(obj);
			break;
		case "menuTitle":
//			$(".menuBtn[cKind=menuTitle]").next().css({display:"none"});
//			obj.next().css({display:"block"});
			$(".menuBtn[cKind=menuTitle]").next().slideUp();
			obj.next().slideDown();
			break;
	}
}

var pageLoad = function(){
//	console.log(getUrlParameter("pType"));
	var pType = getUrlParameter("pType")==""?"rkList":getUrlParameter("pType");
	var cUrl = "/manage/json/index.php";
	var params = {
		pageType : pType
	}
	$.ajax({
		url: cUrl,
		type: "post",
		dataType: "json",
		data: params,
		beforeSend: function() {}, // 서버 요청 전 호출 되는 함수 return false; 일 경우 요청 중단
		success: function (data) {
//			console.log(data);
			if($.cookie("userid")!=undefined){
				$("#loginFrm").css("display", "none");
			}else{
				$("input[name=id]").focus();
			}
			if($("#loginFrm").css("display")=="none"){
				$(".menuBtn[cType="+pType+"]").next().css({"border-color":"#FF0000"});
				$(".menuBtn[cType="+pType+"]").next().find("div").css({"background-color":"#FF0000"});
				$(".menuBtn[cType="+pType+"]").parent().parent().css({display:"block"});
				contentDiv(data, params);
			}
		},
		error: function(request, status, error) {
			console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);		
		}, // 요청 실패.
		complete: function() {} // 요청의 실패, 성공과 상관 없이 완료 될 경우 호출
	});

}

var loginChk = function(params){
	var cUrl = "/manage/json/index.php";
	$.ajax({
		url: cUrl,
		type: "post",
		dataType: "json",
		data: params,
		beforeSend: function() {}, // 서버 요청 전 호출 되는 함수 return false; 일 경우 요청 중단
		success: function (data) {
//			console.log(data);
			if(data.state==1){
		        $.cookie('userid', params.id, { path : '/' });
				$("input[name=id]").val("");
				$("input[name=pwd]").val("");
				alert("로그인 완료");
				location.reload();
			}else{
				alert("로그인 정보가 정확하지 않습니다.");
				$("input[name=id]").focus();
			}
		},
		error: function(request, status, error) {
			console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);		
		}, // 요청 실패.
		complete: function() {} // 요청의 실패, 성공과 상관 없이 완료 될 경우 호출
	});
}

var contentDiv = function(data, params){
//	console.log(data);
	var pageType = params.pageType;
	var strHtml = "";
	switch(pageType){
		case "rkList": case "rcList":
			strHtml = "<div>";

			if(pageType=="rkList"){
				strHtml = strHtml+"	<div><div>입출금리스트(원화)</div><div></div></div>";
			}else{
				strHtml = strHtml+"	<div><div>입출금리스트(코인)</div><div></div></div>";
			}

			strHtml = strHtml+"	<table id='listTbl' class='display'>";
			strHtml = strHtml+"		<thead>";
			strHtml = strHtml+"		<tr>";
			strHtml = strHtml+"			<th>넘버</th>";
			strHtml = strHtml+"			<th>심볼</th>";
			strHtml = strHtml+"			<th>아이디(이름)</th>";
			if(pageType=="rkList"){
				strHtml = strHtml+"			<th>은행</th>";
			}else{
				strHtml = strHtml+"			<th>지갑주소</th>";
			}
			strHtml = strHtml+"			<th>이름</th>";
			if(pageType=="rkList"){
				strHtml = strHtml+"			<th>금액</th>";
			}else{
				strHtml = strHtml+"			<th>수량</th>";
			}
			strHtml = strHtml+"			<th>입/출금</th>";
			strHtml = strHtml+"			<th>상태</th>";
			strHtml = strHtml+"			<th>요청일</th>";
			strHtml = strHtml+"			<th></th>";
			strHtml = strHtml+"		</tr>";
			strHtml = strHtml+"		</thead>";
			strHtml = strHtml+"		<tbody>";

			for(i=0; i<data.length ; i++){
				rIdx = data.length-i;
				strHtml = strHtml+"		<tr cAddr='"+cAddr[data[i].symbol]+"' cIdx='"+data[i].idx+"'>";
				strHtml = strHtml+"			<td>"+rIdx+"</td>";
				strHtml = strHtml+"			<td>"+data[i].symbol+"</td>";
				strHtml = strHtml+"			<td>"+data[i].user_id+"("+data[i].user_name+")"+"</td>";
				if(pageType=="rkList"){
					strHtml = strHtml+"			<td>"+data[i].bank+"</td>";
				}else{
					strHtml = strHtml+"			<td><div class='divBtn' cKind='tranHistory'>"+data[i].address+"</div></td>";
				}
				strHtml = strHtml+"			<td>"+data[i].user_name+"</td>";
				strHtml = strHtml+"			<td>"+data[i].balance+"</td>";
				assType = data[i].assetsType=="0"?"입금":"출금";
				strHtml = strHtml+"			<td>"+assType+"</td>";
				st = data[i].flag=="0"?"확인중":"완료";
				strHtml = strHtml+"			<td>"+st+"</td>";
				strHtml = strHtml+"			<td>"+data[i].regdate+"</td>";
				if(data[i].flag=="0"){
					strHtml = strHtml+"			<td><div class='divBtn' cKind='requestConfirm'>처리</div></td>";
				}else{
					strHtml = strHtml+"			<td><div>처리</div></td>";
				}
				strHtml = strHtml+"		</tr>";
			}
			strHtml = strHtml+"		<tbody>";
			strHtml = strHtml+"	</table>";
			strHtml = strHtml+"</div>";
			break;
		case "cList":
			strHtml = "<div>";
			strHtml = strHtml+"	<div><div>코인리스트</div><div></div></div>";
			strHtml = strHtml+"	<table id='listTbl' class='display'>";
			strHtml = strHtml+"		<thead>";
			strHtml = strHtml+"		<tr>";
			strHtml = strHtml+"			<th>넘버</th>";
			strHtml = strHtml+"			<th>심볼</th>";
			strHtml = strHtml+"			<th>이름</th>";
			strHtml = strHtml+"			<th>금액</th>";
			strHtml = strHtml+"			<th></th>";
			strHtml = strHtml+"		</tr>";
			strHtml = strHtml+"		</thead>";
			strHtml = strHtml+"		<tbody>";
			for(i=0; i<data.length ; i++){
				rIdx = data.length-i;
				strHtml = strHtml+"		<tr cAddr='"+cAddr[data[i].symbol]+"' cIdx='"+data[i].idx+"'>";
				strHtml = strHtml+"			<td>"+(i+1)+"</td>";
				strHtml = strHtml+"			<td>"+data[i].coin+"</td>";
				strHtml = strHtml+"			<td>"+data[i].name+"</td>";
				strHtml = strHtml+"			<td>"+data[i].price+"</td>";
				if(data[i].flag=="0"){
					strHtml = strHtml+"			<td><div class='divBtn' cKind='requestConfirm'>사용</div></td>";
				}else{
					strHtml = strHtml+"			<td><div>사용중</div></td>";
				}
				strHtml = strHtml+"		</tr>";
			}
			strHtml = strHtml+"		</tbody>";
			strHtml = strHtml+"	</table>";
			strHtml = strHtml+"</div>";
			break;
		case "mList":
			strHtml = "<div>";
			strHtml = strHtml+"	<div><div>회원리스트</div><div></div></div>";
			strHtml = strHtml+"	<table id='listTbl' class='display'>";
			strHtml = strHtml+"		<thead>";
			strHtml = strHtml+"		<tr>";
			strHtml = strHtml+"			<th>넘버</th>";
			strHtml = strHtml+"			<th>아이디(이름)</th>";
			strHtml = strHtml+"			<th>이메일</th>";
			strHtml = strHtml+"			<th>연락처</th>";
			strHtml = strHtml+"			<th>등록일</th>";
			strHtml = strHtml+"		</tr>";
			strHtml = strHtml+"		</thead>";
			strHtml = strHtml+"		<tbody>";
			for(i=0; i<data.length ; i++){
				rIdx = data.length-i;
				strHtml = strHtml+"		<tr cAddr='"+cAddr[data[i].symbol]+"' cIdx='"+data[i].idx+"'>";
				strHtml = strHtml+"			<td>"+rIdx+"</td>";
				strHtml = strHtml+"			<td>"+data[i].auth+"("+data[i].username+")</td>";
				strHtml = strHtml+"			<td>"+data[i].email+"</td>";
				strHtml = strHtml+"			<td>"+data[i].mobile+"</td>";
				strHtml = strHtml+"			<td>"+getTIMESTAMP(data[i].regdate)+"</td>";
				strHtml = strHtml+"		</tr>";
			}
			strHtml = strHtml+"		</tbody>";
			strHtml = strHtml+"	</table>";
			strHtml = strHtml+"</div>";
			break;
		case "trList1": case "trList2":
			title = pageType=="trList1"?"거래내역":"거래대기";
			strHtml = "<div>";
			strHtml = strHtml+"	<div><div>"+title+"</div><div></div></div>";
			strHtml = strHtml+"	<table id='listTbl' class='display'>";
			strHtml = strHtml+"		<thead>";
			strHtml = strHtml+"		<tr>";
			strHtml = strHtml+"			<th>넘버</th>";
			strHtml = strHtml+"			<th>구매자</th>";
//			strHtml = strHtml+"			<th>지갑</th>";
			strHtml = strHtml+"			<th>판매자</th>";
//			strHtml = strHtml+"			<th>지갑</th>";
			strHtml = strHtml+"			<th>코인</th>";
			strHtml = strHtml+"			<th>금액</th>";
			strHtml = strHtml+"			<th>수량</th>";
			strHtml = strHtml+"			<th>거래일</th>";
			strHtml = strHtml+"		</tr>";
			strHtml = strHtml+"		</thead>";
			strHtml = strHtml+"		<tbody>";
			for(i=0; i<data.length; i++){
				rIdx = data.length-i;
				strHtml = strHtml+"		<tr cIdx='"+data[i].idx+"'>";
				strHtml = strHtml+"			<td>"+rIdx+"</td>";
				strHtml = strHtml+"			<td>"+data[i].trading_uID+"</td>";
//				strHtml = strHtml+"			<td>"+data[i].Trader_wallet+"</td>";
				strHtml = strHtml+"			<td>"+data[i].buyer_uID+"</td>";
//				strHtml = strHtml+"			<td>"+data[i].buyer_wallet+"</td>";
				strHtml = strHtml+"			<td>"+data[i].coin_name+"</td>";
				strHtml = strHtml+"			<td>"+data[i].trading_money+"</td>";
				strHtml = strHtml+"			<td>"+data[i].trading_amount+"</td>";
				strHtml = strHtml+"			<td>"+data[i].trading_date+"</td>";
				strHtml = strHtml+"		</tr>";
			}
			strHtml = strHtml+"		</tbody>";
			strHtml = strHtml+"	</table>";
			strHtml = strHtml+"</div>";
			break;
	}

	$("#contentDiv").html(strHtml);
	switch(pageType){
		case "rkList": case "rcList": case "mList": case "trList1": case "trList2":
			$("#listTbl").DataTable({
				"order": [[ 0, "desc" ]]
			});
			break;
		case "cList":
			$("#listTbl").DataTable({
				"order": [[ 0, "asc" ]]
			});
			break;
	}
}

var tranHistory = function(obj){
	$("#contentDiv>div:nth-child(2)").remove();
	var wAddr = obj.parent().text();
	var cAddr = obj.parent().parent().attr("cAddr");
	var balance = obj.parent().next().next().text();
	var lType = obj.parent().next().next().next().text();
	var params = {
		apiKey : "freekey",
		token : cAddr,
		type : "transfer",
		limit : 10
	}

//	wAddr = "0xadB0D5dda7aAed2C3d6ABc299BB99F6d5A08bA2B";
//	console.log(wAddr);
//	var cUrl = "http://api.ethplorer.io/getAddressHistory/0xadB0D5dda7aAed2C3d6ABc299BB99F6d5A08bA2B?apiKey=freekey&token=0x81fe677cb2cb26abf471f40cd8256ce9d07fed3a&type=transfer&limit=10";
	var cUrl = "http://api.ethplorer.io/getAddressHistory/"+wAddr;
	$.ajax({
		url: cUrl,
		type: "get",
		dataType: "json",
		data: params,
		async: false,
		crossOrigin: true,
		beforeSend: function() {
			$("#backDiv").css({"display":"block"});
		}, // 서버 요청 전 호출 되는 함수 return false; 일 경우 요청 중단
		success: function (data) {
			var strHtml = "";
			if(data.error){
				strHtml = strHtml+"<div>";
				strHtml = strHtml+"	<div>거래내역확인</div>";
				strHtml = strHtml+"	<table id='historyTbl' class='display'>";
				strHtml = strHtml+"		<thead>";
				strHtml = strHtml+"			<tr>";
				strHtml = strHtml+"				<th>코인네임</th>";
				strHtml = strHtml+"				<th>심볼</th>";
				strHtml = strHtml+"				<th>자리수</th>";
				strHtml = strHtml+"				<th>보낸주소</th>";
				strHtml = strHtml+"				<th>받은주소</th>";
				strHtml = strHtml+"				<th>개수</th>";
				strHtml = strHtml+"			</tr>";
				strHtml = strHtml+"		</thead>";
				strHtml = strHtml+"		<tbody>";
				strHtml = strHtml+"		</tbody>";
				strHtml = strHtml+"	</table>";
				strHtml = strHtml+"</div>";
			}else{
				$.each(data, function(i, obj) {
					strHtml = strHtml+"<div>";
					strHtml = strHtml+"	<div>거래내역확인</div>";
					strHtml = strHtml+"	<table id='historyTbl' class='display'>";
					strHtml = strHtml+"		<thead>";
					strHtml = strHtml+"			<tr>";
					strHtml = strHtml+"				<th>코인네임</th>";
					strHtml = strHtml+"				<th>심볼</th>";
					strHtml = strHtml+"				<th>자리수</th>";
					strHtml = strHtml+"				<th>보낸주소</th>";
					strHtml = strHtml+"				<th>받은주소</th>";
					strHtml = strHtml+"				<th>개수</th>";
					strHtml = strHtml+"				<th>거래일</th>";
					strHtml = strHtml+"			</tr>";
					strHtml = strHtml+"		</thead>";
					strHtml = strHtml+"		<tbody>";
					$.each(obj, function() {
//						console.log("받는사람"+(this.to==wAddr), this.to+":"+wAddr);
//						console.log("보내는사람"+(this.from==wAddr), this.from+":"+wAddr);
//						console.log(this.from.length, this.to.length, wAddr.length);
//						if(balance==this.value.cutStr(this.tokenInfo.decimals)){
							if(lType=="입금"){
								if(this.to==wAddr.toLowerCase()){
									strHtml = strHtml+"		<tr>";
									strHtml = strHtml+"			<td>"+this.tokenInfo.name+"</td>";
									strHtml = strHtml+"			<td>"+this.tokenInfo.symbol+"</td>";
									strHtml = strHtml+"			<td>"+this.tokenInfo.decimals+"</td>";
									strHtml = strHtml+"			<td>"+this.from+"</td>";
									strHtml = strHtml+"			<td>"+this.to+"</td>";
									strHtml = strHtml+"			<td>"+this.value.cutStr(this.tokenInfo.decimals).comma(3)+"."+this.value.right(this.tokenInfo.decimals)+"</td>";
									strHtml = strHtml+"			<td>"+getTIMESTAMP(this.timestamp)+"</td>";
									strHtml = strHtml+"		</tr>";
								}
							}
	/*						else{
								if(this.from==wAddr.toLowerCase()){
									strHtml = strHtml+"		<tr>";
									strHtml = strHtml+"			<td>"+this.tokenInfo.name+"</td>";
									strHtml = strHtml+"			<td>"+this.tokenInfo.symbol+"</td>";
									strHtml = strHtml+"			<td>"+this.tokenInfo.decimals+"</td>";
									strHtml = strHtml+"			<td>"+this.from+"</td>";
									strHtml = strHtml+"			<td>"+this.to+"</td>";
									strHtml = strHtml+"			<td>"+this.value.cutStr(this.tokenInfo.decimals).comma(3)+"."+this.value.right(this.tokenInfo.decimals)+"</td>";
									strHtml = strHtml+"			<td>"+getTIMESTAMP(this.timestamp)+"</td>";
									strHtml = strHtml+"		</tr>";
								}
							}
*/
//						}
					});
					strHtml = strHtml+"		</tbody>";
					strHtml = strHtml+"	</table>";
					strHtml = strHtml+"</div>";
				});
			}

			$("#contentDiv").append(strHtml);
//			$("#backDiv").html(strHtml);
			$("#historyTbl").DataTable({
				"order": [[ 0, "desc" ]]
			});
		},
		error: function(request, status, error) {
			console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);		
		}, // 요청 실패.
		complete: function() {
			$("#backDiv").css({"display":"none"});
		} // 요청의 실패, 성공과 상관 없이 완료 될 경우 호출
	});
}
var dataUpdate = function(params){
	cUrl = "/manage/json/index.php";
	$.ajax({
		url: cUrl,
		type: "post",
		dataType: "json",
		data: params,
		beforeSend: function() {}, // 서버 요청 전 호출 되는 함수 return false; 일 경우 요청 중단
		success: function (data) {
//			console.log(data);

			if(data.state==1){
				alert(data.msg);
			}else{
				alert(data.msg);
				pageLoad();
			}

//			contentDiv(data, params);
		},
		error: function(request, status, error) {
			console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);		
		}, // 요청 실패.
		complete: function() {} // 요청의 실패, 성공과 상관 없이 완료 될 경우 호출
	});

}

var getUrlParameter = function(name) {
    name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
    var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
    var results = regex.exec(location.search);
    return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
}

var cookieClear = function(){
	var cookies = $.cookie();
	for(var cookie in cookies) {
	   $.removeCookie(cookie, { path: '/' });
	} 
}

var pageMove = function(tUrl){
	if(tUrl == "/"){
		openNewWindow = window.open("about:blank");
		openNewWindow.location.href=tUrl;
	}else{
		document.location.href=tUrl;
	}
}