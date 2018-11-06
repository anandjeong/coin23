var cUrl="";
var pageType = "";
$(document).ready(function(){
//	console.log($("input[name=sesBtc]").val(), $("input[name=sesEth]").val());
	uCnt = $(location).attr('href').split("/").length;
	var cUrl = "";
	for(i=0; i<$(location).attr('href').split("/").length-3; i++){
		if(i==0){
			pageType = $(location).attr('href').split("/")[i+3];
		}
		cUrl = cUrl+"/"+$(location).attr('href').split("/")[i+3];
	}
	pageLoad(cUrl);

	$(document).on("click", "#bg_layout", function(event){
		$target = $(event.target);
		if($target.attr("id")=="bg_layout"){
			$(this).css({display:"none"});
		}
	});
	 

	$(document).on("click", ".btnDiv, .aBtn, .btnClick, .sBtn", function(){
		clickEvent($(this));
	});
  


	//setInterval(function() {getData_BTC();getState_ETH(); }, 3000); 
 
});


var pageLoad = function(cUrl){
//	console.log(pageType);
	var params = {
		pType: "pageload"
	}
	$.ajax({
		url: cUrl,
		type: "post",
		dataType: "html",
		data: params,
		beforeSend: function() {}, // 서버 요청 전 호출 되는 함수 return false; 일 경우 요청 중단
		success: function (data) {
			$("head").html(data.split("<head>")[1].split("</head>")[0]);
			if(pageType!="forgotPassword.html"){
				$("section").each(function(i){
					if(i==0){
						$(this).html(data.split('<section class="content" style="background: rgba(39,59,95,1.00)">')[1].split("</section>")[0]);
						setTimeout(function(){
							switch(pageType){
								case "trading":
									tradingChart();
									break;
								case "wallets.html":
									walletsInfo();
									break;

							}
						}, 500);
					}
				});
			}

			loginSt = $("input[name=loginSt]").val();
			if(loginSt==1){
				$(".navbar-right li").eq(0).css("display", "none");
				$(".navbar-right li").eq(2).css("display", "none");
				$(".luna-nav li").eq(0).css("display", "none");
				$(".luna-nav li").eq(1).css("display", "none");
				$(".aBtn[cKind=logout]>span").text("로그아웃("+$("input[name=sesId]").val()+") :");

				switch(pageType){
					case "wallets":
						console.log($("input[name=rSymbol]").val());
						$("input[name=user_id]").val($("input[name=sesId]").val());
						$("input[name=user_name]").val($("input[name=sesName]").val());
						var symbol = $("input[name=rSymbol]").val();
						if(symbol!=""){
							$("input[name=symbol]").val(symbol);
							addr = symbol=="BTC"?$("input[name=sesBtc]").val():$("input[name=sesEth]").val();
							if($("button[type=submit]").attr("cKind")=="deposit"){
								$("input[name=address]").val(addr);
							}

							if(symbol=="BTC"){
								$("input[name=address]").attr("placeholder", "BTC Address");
								$("input[name=address]").prev().text("BTC 주소");
							}else{
								$("input[name=address]").attr("placeholder", "ETH Address");
								$("input[name=address]").prev().text("ETH 주소");
							}

							$("input[name=balance]").prev().text(symbol+" 수량");
							$("h3").text(symbol+" 입금신청 (신청서를 제출하고 아래 주소로 보내세요)");
						}
						break;
					case "wallets.html":
						var sesBtc = $("input[name=sesBtc]").val();
						var sesEth = $("input[name=sesEth]").val();
						$("a[cKind=walletCreate]").each(function(i){
							switch(i){
								case 0:
									if(sesBtc!=""){
										$(this).removeClass("aBtn");
										$(this).addClass("disabledAbtn");
										$(this).text("생성완료");
									}
									break;
								case 1:
									if(sesEth!=""){
										$(this).removeClass("aBtn");
										$(this).addClass("disabledAbtn");
										$(this).text("생성완료");
									}
									break;
							}
						});

//						$("a[cKind=walletCreate]").removeClass("aBtn");
						break;
				}
			}else{
				$(".navbar-right li").eq(1).css("display", "none");
			}
		},
		error: function(request, status, error) {
			console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);		
		}, // 요청 실패.
		complete: function() {} // 요청의 실패, 성공과 상관 없이 완료 될 경우 호출
	});
}

var clickEvent = function(obj){
	var cKind = obj.attr("cKind");
	var params = {}
	switch(cKind){
		case "emailChk": case "authCodeChk":
			if($("input[name=loginName]").val()!=""){
				params = {
					cKind : cKind,
					email : $("input[name=loginName]").val()
				}
				cUrl = "/json/mailChk.php";
				dataChk(cUrl, params);
			}else{
				alert("인증요청 E-MAIL을 입력하세요.");
				$("input[name=loginName]").focus();
			}
			break;
		case "idChk":
			if($("input[name=userid]").val()!=""){
				params = {
					cKind : cKind,
					userid : $("input[name=userid]").val()
				}
				cUrl = "/json/idChk.php";
				dataChk(cUrl, params);
			}else{
				alert("사용할 ID를 입력하세요.");
				$("input[name=userid]").focus();
			}
			break;
		case "signUp": case "infoModify":
			if($("input[name=loginName]").val()!=""){
				params = {
					cKind : cKind,
					uId : $("input[name=userid]").val(),
					uName : $("input[name=username]").val(),
					email : $("input[name=loginName]").val(),
					pwd : $("input[name=loginPassword]").val(),
					hp : $("input[name=hp]").val(),
					recoId : $("input[name=recoId]").val()
				}
				cUrl = "/json/mailChk.php";
				if(dataChk(cUrl, params)=="SUCCESS"){
					var uId = $("input[name=userid]");
					var uName = $("input[name=username]");
					var pwd_01 = $("input[name=loginPassword]");
					var pwd_02 = $("input[name=confirmPassword]");
					var hp = $("input[name=hp]");
					var recoId = $("input[name=recoId]").val();

					if(uId.val()==""){
						alert("사용하실 ID를 입력하세요.");
						uId.focus();
						return false;
					}

					if(uName.val()==""){
						alert("이름을 입력하세요.");
						uName.focus();
						return false;
					}

					if(pwd_01.val()==""){
						alert("비밀번호를 입력하세요.");
						pwd_01.focus();
						return false;
					}
					if(pwd_02.val()==""){
						alert("비밀번호를 입력하세요.");
						pwd_02.focus();
						return false;
					}

					if(pwd_01.val()!=pwd_02.val()){
						alert("비밀번호가 일치하지 않습니다.");
						return false;
					}

					if(hp.val()==""){
						alert("핸드폰 번호를 입력하세요.");
						hp.focus();
						return false;
					}

					if(hp.val()==""){
						alert("핸드폰 번호를 입력하세요.");
						hp.focus();
						return false;
					}
					if(cKind=="signUp"){
						if($("input[name=termCheck]").is(":checked")!=true){
							alert("이용약관 및 개인정보 취급방침관련 안내를 읽고 동의를 하셔야 됩니다.");
							return false;
						}
					}
//					console.log($("input[name=termCheck]").is(":checked"));
//					console.log("가입시작");
//					console.log(params);
					cUrl = "/json/signUp.php";
//					console.log(params);
					dataWrite(cUrl, params);
				}else{
					alert("인증완료/신청이 되지 않은 E-MAIL입니다.");
					$("input[name=loginName]").focus();
				}
			}else{
				alert("아이디로 사용할 E-MAIL을 입력하세요.");
				$("input[name=loginName]").focus();
			}
			break;
		case "signIn":
			var uId = $("input[name=loginName]");
			var uPwd = $("input[name=loginPassword]");
			if(uId.val()==""){
				alert("아이디(E-MAIL)을 입력하세요.");
				uId.focus();
				return false;
			}
			if(uPwd.val()==""){
				alert("비밀번호를 입력하세요.");
				uPwd.focus();
				return false;
			}
			params = {
				cKind : cKind,
				id : uId.val(),
				pwd : uPwd.val()
			}
			cUrl = "/json/signIn.php";
			dataChk(cUrl, params);
			break;
		case "logout":
			params = {
				cKind : cKind
			}
			cUrl = "/json/logout.php";
			dataChk(cUrl, params);
			break;
		case "walletCreate":
			var symbol = obj.attr("symbol");

			if(symbol=="BTC"){
				alert("서비스 준비중입니다.");
			}else{
				params = {
					symbol : symbol
				}
				walletCreate(params);
			}
			break;
		case "deposit":
//			console.log("입금");
			assetsInfo(0);
			break;
		case "withdraw":
//			console.log("출금");
			assetsInfo(1);
			break;
		case "manage":
			pageMove("/manage/");
			break;
		case "inCoin":
			var symbol = obj.attr("symbol");

			if(symbol=="BTC"){
				alert("서비스 준비중입니다.");
			}else{
				pageMove("/wallets/coin_in.html?symbol="+obj.attr("symbol"));
			}
			break;
		case "outCoin":
			var symbol = obj.attr("symbol");

			if(symbol=="BTC"){
				alert("서비스 준비중입니다.");
			}else{
				pageMove("/wallets/coin_out.html?symbol="+obj.attr("symbol"));
			}
			break;
		case "history":
			$(".btnDiv[cKind='selSymbol']").removeClass("curSymbol");
			$(".btnDiv[cKind='selSymbol']:nth-child(1)").addClass("curSymbol");
			historyList();
			$("#contentDiv>div:nth-child(1)>div:nth-child(1)>span").text($("input[name=sesId]").val());
			$("#bg_layout").css({display:"block"});
			$("#contentDiv>div:nth-child(2)").animate({ scrollTop: 0 }, "fast");
			break;
		case "close":
			var divName = obj.attr("cType");
			divName = divName==""?"bg_layout":divName;
			$("#"+divName).css({display:"none"});
			break;
		case "selSymbol":
			$(".btnDiv[cKind='selSymbol']").removeClass("curSymbol");
			$(obj).addClass("curSymbol");
			historyList(obj);
			$("#contentDiv>div:nth-child(2)").animate({ scrollTop: 0 }, "fast");
			break;

	}
}

var historyList = function(obj){
	var params = {}
	params.auth = $("input[name=sesId]").val();
	if(obj==undefined){
		params.symbol = "ALL";
	}else{
		params.symbol = obj.text();
	}
	cUrl = "/json/history.php";
	$.ajax({
		url: cUrl,
		type: "post",
		dataType: "json",
		data: params,
		async: false,
		beforeSend: function() {}, // 서버 요청 전 호출 되는 함수 return false; 일 경우 요청 중단
		success: function (data) {
//			console.log(data.results);
			$("#contentDiv>div:nth-child(2)").html("");
			var strSql="";
/*
			strSql = strSql+"<table>";
			strSql = strSql+"	<thead>";
			strSql = strSql+"		<tr>";
			strSql = strSql+"			<th>상태</th>";
			strSql = strSql+"			<th>넘버</th>";
			strSql = strSql+"			<th>심볼</th>";
			strSql = strSql+"			<th>수량</th>";
			strSql = strSql+"			<th>은행&지갑</th>";
			strSql = strSql+"			<th>등록일</th>";
			strSql = strSql+"		</tr>";
			strSql = strSql+"	</thead>";
			strSql = strSql+"	<tbody>";
			$.each(data.results, function(i, obj) {
				stName = obj.assetsType==0?"입금":"출금";
				strSql = strSql+"		<tr>";
				strSql = strSql+"			<td>"+stName+"</td>";
				strSql = strSql+"			<td></td>";
				strSql = strSql+"			<td>"+obj.symbol+"</td>";
				strSql = strSql+"			<td>"+obj.balance+"</td>";
				if(obj.assetsType==0){
					if(obj.symbol=="KRW"){
						bank=obj.bank;
					}else{
						bank=obj.address;
					}
				}else{
					if(obj.symbol=="KRW"){
						bank=obj.bank+"/"+obj.address;
					}else{
						bank=obj.address
					}
				}
				strSql = strSql+"			<td>"+bank+"</td>";
				strSql = strSql+"			<td>"+obj.regdate+"</td>";
				strSql = strSql+"		</tr>";
			});
			strSql = strSql+"	</tbody>";
			strSql = strSql+"</table>";
*/
			$.each(data.results, function(i, obj) {
				stName = obj.assetsType==0?"입금":"출금";
				stBgcolor = obj.flag==0?"":" background-color:#FF0000; color:#FFFFFF;";
				strSql = strSql+"<div style='padding:5px 0px; border-bottom:1px solid #000000;"+stBgcolor+"'>";
				strSql = strSql+"	<div style='display:flex; flex-direction:row;'>";
				strSql = strSql+"		<div style='flex:0 0 14%;'>"+stName+"요청</div>";
				strSql = strSql+"		<div style='flex:0 0 18%;'>No: "+(data.results.length-i)+"</div>";
				strSql = strSql+"		<div style='flex:0 0 28%;'>"+obj.symbol+": "+obj.balance+"</div>";
				strSql = strSql+"		<div style='flex:1 1 auto;'>Date: "+obj.regdate+"</div>";
				strSql = strSql+"	</div>";
				strSql = strSql+"	<div>";
				if(obj.assetsType==0){
					if(obj.symbol=="KRW"){
						strSql = strSql+"		<div>은행명: "+obj.bank+"</div>";
					}else{
						strSql = strSql+"		<div>지갑주소: "+obj.address+"</div>";
					}
				}else{
					if(obj.symbol=="KRW"){
						strSql = strSql+"		<div>은행명: "+obj.bank+"/"+obj.address+"</div>";
					}else{
						strSql = strSql+"		<div>지갑주소: "+obj.address+"</div>";
					}
				}

				strSql = strSql+"	</div>";
				strSql = strSql+"</div>";
			});

			$("#contentDiv>div:nth-child(2)").html(strSql);
/*
			$.each(data, function(i, obj) {
				console.log(obj.results)
				$.each(obj, function() {
//					console.log(this.results.address);
				});
			});
*/
		},
		error: function(request, status, error) {
			console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);		
		}, // 요청 실패.
		complete: function() {} // 요청의 실패, 성공과 상관 없이 완료 될 경우 호출
	});
}

var dataWrite = function(cUrl, params){
	var result = "";
	$.ajax({
		url: cUrl,
		type: "post",
		dataType: "json",
//		dataType: "html",
		data: params,
		async: false,
		beforeSend: function() {}, // 서버 요청 전 호출 되는 함수 return false; 일 경우 요청 중단
		success: function (data) {
			console.log(data);

			var msg = "";
			if(data.state=="SUCCESS"){
				msg = data.msg;
				frmReset("#frmSignUp");
			}else{
				msg = data.msg;
				switch(params.cKind){
					case "infoModify": case "signUp":
						alert(msg);
//						frmReset("#frmSignUp");
						return false;
						break;
				}
			}
			if(msg==""){
				result = data.state;
			}else{
				alert(msg);
				switch(params.cKind){
					case "infoModify": case "signUp":
						pageMove("/login.html");
						break;
				}
			}

		},
		error: function(request, status, error) {
			console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);		
		}, // 요청 실패.
		complete: function() {} // 요청의 실패, 성공과 상관 없이 완료 될 경우 호출
	});
	var result = "";
}

var dataChk = function(cUrl, params){
	var result = "";
	$.ajax({
		url: cUrl,
		type: "post",
		dataType: "json",
		data: params,
		async: false,
		beforeSend: function() {
			
		}, // 서버 요청 전 호출 되는 함수 return false; 일 경우 요청 중단
		success: function (data) {
//			console.log(data);

			var msg = "";
			if(data.state=="SUCCESS"){
				switch(params.cKind){
					case "emailChk": case "authCodeChk":
						msg = "인증요청 메일을 보내드렸습니다.\n입력하신 "+params.email+"을 확인하세요.";
						break;
					case "idChk":
						msg = data.msg;
						break;
					case "signUp":
						msg = "";
						break;
					case "signIn":
						msg = "로그인이 완료\n서비스 이용이 가능하십니다.";
						break;
					case "logout":
						msg = "로그아웃이 되었습니다.\n서비스를 이용하시려면 로그인 후 사용해주세요.";
						break;
				}
			}else{
				msg = data.msg;
				switch(params.cKind){
					case "signIn":
						frmReset("#frmLogin");
						$("input[name=loginName]").focus();
						break;
				}
			}
			if(msg==""){
				result = data.state;
			}else{
				alert(msg);
				switch(params.cKind){
					case "signIn":
						if(data.state=="SUCCESS"){
							pageMove("/wallets.html");
						}
						break;
					case "logout":
						pageMove("/login.html");
						break;
				}
			}

		},
		error: function(request, status, error) {
			console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);		
		}, // 요청 실패.
		complete: function() {} // 요청의 실패, 성공과 상관 없이 완료 될 경우 호출
	});
	return result;
}

var walletCreate = function(params){
	var cUrl = "";
	var dType = "";
	switch(params.symbol){
		case "BTC":
			cUrl = "http://203.235.28.219/CreateAccountBTC.html?label="+$("input[name=sesId]").val();
			dType = "html";
			break;
		case "ETH":
			cUrl = "/json/curl.php";
			params.cUrl = "https://wt-e79da0981ef1f312f9ba7d4331653992-0.run.webtask.io/generate-ethereum-account";
			dType = "json";
			break;
	}
//	console.log(cUrl);
//	return false;

	

	$.ajax({
		url: cUrl,
		type: "get",
		dataType: dType,
		data: params,
		async: true,
		crossOrigin: true,
		beforeSend: function() {}, // 서버 요청 전 호출 되는 함수 return false; 일 경우 요청 중단
		success: function (data) {
//			console.log(data);

			addr = "";
			switch(params.symbol){
				case "BTC":
					addr = data.split("|")[3];
					priKey = data.split("|")[0];
					break;
				case "ETH":
					obj = $.parseJSON(data);
					addr = obj.address;
					priKey = obj.privateKey;
					break;
			}

			params.priKey = priKey;
			
			alert(walletInfo(params, addr));
//			walletInfo(params, addr);
//			location.reload();

//			if(data.split("|")[3]!=""){
//				alert(params.symbol+"지갑주소 생성이 완료되었습니다.");
//			}else{
//				alert(params.symbol+"지갑주소 생성중 오류가 발생하였습니다.");
//			}

		},
		error: function(request, status, error) {
			console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);		
		}, // 요청 실패.
		complete: function() {} // 요청의 실패, 성공과 상관 없이 완료 될 경우 호출
	});
}

var walletInfo = function(params, addr){
	params.id=$("input[name=sesId]").val();
	params.addr=addr;
//	params.addr="";


	var msg = "";
	cUrl = "/json/walletWrite.php";
	$.ajax({
		url: cUrl,
		type: "get",
		dataType: "json",
//		dataType: "html",
		data: params,
		async: false,
		crossOrigin: true,
		beforeSend: function() {}, // 서버 요청 전 호출 되는 함수 return false; 일 경우 요청 중단
		success: function (data) {
//			console.log(data);
			msg = data.msg;
		},
		error: function(request, status, error) {
			console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);		
		}, // 요청 실패.
		complete: function() {} // 요청의 실패, 성공과 상관 없이 완료 될 경우 호출
	});

	return msg;
}

var assetsInfo = function(assetsType){
	cUrl = "/json/assetsInfo.php";
	var frm = $("form[name=inputFrm]");
	var st = 0;
	var symbol = $("input[name=rSymbol]").val();
	frm.find("input").each(function(i){
		if($(this).attr("name")!="balance"){
			if($(this).val()==""){
				alert($(this).prev().text()+"을(를) 입력하세요.");
				$(this).focus();
				st = 1;
				return false;
			}
		}else{
			if($(this).val()=="0"){
				alert("입금요청 "+symbol+"를(을) 정확히 입력하세요.");
				$(this).focus();
				st = 1;
				return false;

			}
		}
	});

	if(st==1){
		return false;
	}
	var params = frm.serializeArray();

	params.push({name: 'assetsType', value: assetsType});
	$.ajax({
		url: cUrl,
		type: "post",
		dataType: "html",
		data: params,
		async: false,
		crossOrigin: true,
		beforeSend: function() {}, // 서버 요청 전 호출 되는 함수 return false; 일 경우 요청 중단
		success: function (data) {
			console.log(data);

			var msg = "";
			if(data==1){
				msg = "입금내역 정보가 전송되었습니다.";
				if(assetsType==1){
					msg = "출금 신청이 완료되었습니다.";
				}
			}else{
				msg = "요청하신 작업이 오류로 인해 취소되었습니다.";
				if(assetsType==1){
					msg = "보유자산이 부족합니다.";
				}
			}

			alert(msg);
			pageMove("/wallets.html");

/*
			frmReset($("form[name=inputFrm]"));
			$("input[name=user_id]").val($("input[name=sesId]").val());
			if(symbol!="KRW"){
				$("input[name=user_name]").val($("input[name=sesName]").val());

				addr = symbol=="BTC"?$("input[name=sesBtc]").val():$("input[name=sesEth]").val();
				$("input[name=address]").val(addr);
			}
*/
		},
		error: function(request, status, error) {
			console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);		
		}, // 요청 실패.
		complete: function() {} // 요청의 실패, 성공과 상관 없이 완료 될 경우 호출
	});	

}

var walletsInfo = function(){
	cUrl = "/json/assetsManage.php";
	var params = {
		user_id : $("input[name=sesId]").val()
	}

	$.ajax({
		url: cUrl,
		type: "post",
		dataType: "json",
		data: params,
		async: false,
		crossOrigin: true,
		beforeSend: function() {}, // 서버 요청 전 호출 되는 함수 return false; 일 경우 요청 중단
		success: function (data) {
			console.log(data.length);
			if(data.length>0){
				$(".infoList>tbody>tr").each(function(i){
					$(this).find("td").css({"padding": "10px 10px"});
					$(this).find("td:nth-child(3)").css({"text-align": "right", "padding": "10px 20px"});
					$(this).find("td:nth-child(4)").css({"text-align": "right", "padding": "10px 20px"});
					$(this).find("td:nth-child(3)").find("span").text(data[i].balance);
					$(this).find("td:nth-child(4)").find("span").text(data[i].bal_01-data[i].bal_02);
				});
			}

//			$(".krwInfo td").eq(2).find("span").text(data[0]);
//			$(".krwInfo td").eq(3).find("span").text(Number(data[3]).toFixed(2));
		},
		error: function(request, status, error) {
			console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);		
		}, // 요청 실패.
		complete: function() {} // 요청의 실패, 성공과 상관 없이 완료 될 경우 호출
	});	
}



var frmReset = function(formName){
	$(formName).each(function() {  
		this.reset();
	});
}

var pageMove = function(tUrl){
	console.log(tUrl);
	if(tUrl == "/manage/"){
		openNewWindow = window.open("about:blank");
		openNewWindow.location.href=tUrl;
	}else{
		document.location.href=tUrl;
	}
}


var tradingChart = function(){
	var data1 = [ [0, 3], [1, 6], [2, 8], [3, 9], [4, 12], [5, 14], [6, 15], [7, 12],
		[8, 14], [9, 12], [10, 11], [11, 10], [12, 14], [13, 16], [14, 15], [15, 15],
		[16, 16], [17, 12], [18, 13], [19, 15], [20, 16], [21, 18], [22, 20], [23, 23],
		[24, 22], [25, 21], [26, 20], [27, 17], [28, 15], [29, 14], [30, 13], [31, 10]];

   
	var chartUsersOptions = {
		lines: {
			show: true,
			fill: false,
			lineWidth: 2,
			fillColor: "#f6a821"
		},
		grid: {
			borderWidth: 0
		}
	};

	$.plot($("#flotExample2"), [data1], chartUsersOptions);
}