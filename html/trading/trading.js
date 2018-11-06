function contract(objTradingContract) 
{ 

    $.post( "trading_update.php", { data: objTradingContract }).done(function( data ) 
    {
        alert( "채결 완료되었습니다."   );
         location.reload();
    });//$.post 

}

function chkMyBalance(wallet,myBalance,amount) 
{


    var clacState = Number(myBalance)> Number(amount)? true : false;
    /* $.post( "getMyBalance.php", { wallet: wallet })
            .done(function( data ) {
                alert( "내 작고확인 : 1,000,000 " + data );
                myBalance = 1000000;
                $('#modal_myBalance').html(myBalance); 
 
            });//$.post*/

    //$('#modal_myBalance').html(comma(myBalance));  
    ///alert(  Number(myBalance)+"; " + Number(amount)+"|| "+clacState );
    return clacState;


}

function trading_list(loginID) 
{

 
    var jURL ="trading_deal_list.php";
    var  param =  {trade_state: 1};

    $.ajax({
        type: "POST",
        async: false,
        url: jURL,
        data:param,
        cache: false,
        //dataType: "json",
        success: function(data)
        {

            var obj = $.parseJSON(data);
            var tmpText = "";
             var tmpText2 = "";
            var deal_list = "";

            var registerID = "";
  
            for(var i in obj)
            {

                registerID = obj[i].trading_uID;

                if(obj[i].trading_kind =="B")
                {
                    tmpText = "매도";
                    tmpText2 = "매수";
                }
                else if(obj[i].trading_kind =="S")
                {   
                    tmpText = "매수";
                    tmpText2 = "매도";
                }

                deal_list+="<tr id='tr_ord_"+obj[i].idx+"'>";
                deal_list+="    <td><span class='view_state' >"+obj[i].coin_name+"</span></td>";
                deal_list+="    <td>"+tmpText2+"</td>";
                deal_list+="    <td style='text-align: right;'>"+comma(obj[i].trading_money)+"</td>";
                deal_list+="    <td style='text-align: right;'>"+comma(obj[i].trading_amount)+"</td>";
                
                deal_list+="    <td>";
               /// if(loginState)
               /// {
                if(loginID == registerID)
                {
                    deal_list+="        <a href='#' id='ord_"+obj[i].idx+"' class='btn btn-cancle btn-w-md btn-success btn-squared'>취소</a>";

                }else{
                    deal_list+="        <a href='#' id='order_"+obj[i].idx+"_"+obj[i].coin_name+"_"+obj[i].trading_money+"_"+obj[i].trading_amount+"_"+obj[i].trading_kind+"' class='btn btn-order btn-w-md btn-success btn-squared'>거래</a>";

                }
           
                deal_list+="    <td>";
                deal_list+="</tr>";


            } 



            $('#deal_list tr:first').after(deal_list); 


        },
        error:function(e){
            alert(" lawData 에러  ");
        }
    });

}//func trading_list


function trading_mylist(divID,loginID) 
{


    var jURL ="trading_deal_mylist.php";
    var  param =  {loginID:loginID};

    $.ajax({
        type: "POST",
        async: false,
        url: jURL,
        data:param,
        cache: false,
        //dataType: "json",
        success: function(data)
        {

            var obj = $.parseJSON(data);
            var tmpText = "";
            var deal_list = "";
            var registerID = "";

                deal_list+="<div  style='width:100%; height :30px; border-bottom: 1px #000 solid;padding-top:5px;'>";
                deal_list+="    <div  class='view_state' style='width:13%; display:inline-block;'> 날짜 </div>";
                deal_list+="    <div  class='view_state' style='width:5%; display:inline-block;'> coin </div>";
                deal_list+="    <div style='width: 5%; display:inline-block;'>구분</div>";
                deal_list+="    <div style='text-align: right; width:5%; display:inline-block;>금액</div>";
                deal_list+="    <div style='text-align: right; width:5%; display:inline-block;'>수량</div>";
                
                deal_list+="    <div style=' width:13%; display:inline-block; '>거래자</div>";
                 deal_list+="    <div style=' width:40%; display:inline-block; '>거래자주소</div>";
                deal_list+="    <div style=' width:10%; display:inline-block;'> 구분 </div>";
                deal_list+="</div>";

            for(var i in obj)
            {

                registerID = obj[i].trading_uID;

                if(obj[i].trading_kind =="B")
                {
                    tmpText = "매도";
                    tmpText2 = "매수";
                }
                else if(obj[i].trading_kind =="S")
                {   
                    tmpText = "매수";
                    tmpText2 = "매도";
                }

                deal_list+="<div id='tr_ord_"+obj[i].idx+"'  style='width:100%;height :30px; border-bottom: 1px #dadada solid;'>";
                deal_list+="    <div  class='view_state' style='width:13%; display:inline-block;'>"+obj[i].trading_date.substring( 0, 10 )+"</div>";
                deal_list+="    <div  class='view_state' style='width:5%; display:inline-block;'>"+obj[i].coin_name+"</div>";
                deal_list+="    <div style='width: 5%; display:inline-block;'>"+tmpText+"</div>";
                deal_list+="    <div style='text-align: right; width:5%; display:inline-block;>"+comma(obj[i].trading_money)+"</div>";
                deal_list+="    <div style='text-align: right; width:5%; display:inline-block;'>"+comma(obj[i].trading_amount)+"</div>";
                
                deal_list+="    <div style=' width:13%; display:inline-block; background-color: #DADADA;padding-left:5px;'>"+obj[i].buyer_uID+"</div>";
                 deal_list+="    <div style=' width:40%; display:inline-block; background-color: #DADADA;padding-left:5px;'>"+obj[i].buyer_wallet+"</div>";

                if(obj[i].trading_amount > 0)
                   deal_list+="    <div style=' width:10%; display:inline-block;padding-left:5px;'>거래완료</div>";
                else{
                    deal_list+="    <div style=' width:10%; display:inline-block;padding-left:5px;'>";
                    deal_list+="        <div style=' width:45%; display:inline-block;'  id='order_"+obj[i].idx+"_"+obj[i].coin_name+"_"+obj[i].trading_money+"_"+obj[i].trading_amount+"_"+obj[i].trading_kind+"' class=''>거래 </div> ";
                    deal_list+="        <div style=' width:45%; display:inline-block;' id='ord_"+obj[i].idx+"' class=' '>취소 </div>";
                    deal_list+="    </div>";
                }

                deal_list+="</div>";


            } 



            $('#'+divID).html(deal_list); 


        },
        error:function(e){
            alert(" lawData 에러  ");
        }
    });

}//func trading_mylist


// 현재 채결 
function trading_deal_list()
{


    var jURL ="trading_deal_ok_list.php";
    var  param =  {trade_state: 1};

    $.ajax({
        type: "POST",
        async: false,
        url: jURL,
        data:param,
        cache: false,
        //dataType: "json",
        success: function(data)
        {

            var obj = $.parseJSON(data);
            var tmpText = "";
            var deal_list = "";
            var sum = 0;
            var trading_money= 0;
            var trading_amount= 0;

            for(var i in obj)
            {
                trading_money=obj[i].trading_money;
                trading_amount= obj[i].trading_amount;
                if(obj[i].trading_kind =="B")
                    tmpText = "매도";
                else    if(obj[i].trading_kind =="S")
                    tmpText = "매수";

                deal_list+="<tr id='tr_ord_"+obj[i].idx+"'>";
                deal_list+="    <td> "+obj[i].account_date+" </td>";
                deal_list+="    <td>"+tmpText+"</td>";
                deal_list+="    <td style='text-align: right;'>"+comma(trading_money)+"</td>";
                deal_list+="    <td style='text-align: right;'>"+comma(trading_amount)+"</td>";


                sum = trading_money *  trading_amount;

                deal_list+="    <td style='text-align: right;'>"+comma(sum)+"</td>";
                deal_list+="</tr>"; 
            }  

            //$('#transactions_history tr:first').after(deal_list);  

             $('#transactions_history>tbody').html(deal_list); 


        },
        error:function(e){
            alert(" transactions_history 에러  ");
        }
    });
}//function trading_deal_list()


function getData_BTC(){
   var url = "https://api.bithumb.com/public/ticker";
   $.getJSON(url, function(result){ 
    var data = "";
    data = [result.data];

    var buy_price = "<tr'>"
    buy_price += "<td>"+comma(data[0].buy_price)+" | "+data[0].date+"</td>";
    buy_price += "<td>"+data[0].units_traded+"</td>";
    buy_price += "<td>누적수량(BTC)</td>";
    buy_price += "</tr>";
                 //$('#buy').prepend(appendData);

                 var sell_price = "<tr'>"
                 sell_price += "<td style='text-align: right;'>"+comma(data[0].sell_price)+" | "+data[0].date+"</td>";
                 sell_price += "<td>"+data[0].units_traded+"</td>";
                 sell_price += "<td>"+data[0].volume_7day+"</td>";
                 sell_price += "</tr>";
                 //$('#buy').prepend(appendData);
                 $('#table_buy tr:first').after(buy_price); 
                 $('#table_sell tr:first').after(sell_price); 
             });   
}


function getState_BTC(){
   var url = "https://api.bithumb.com/public/transaction_history";
   $.getJSON(url, function(result){ 
    var data = "";
    data =  result.data ;

    var tmpType="";

    for (i in result.data) {
               // alert(result.data[i].cont_no );
               if(result.data[i].type=="ask")
                tmpType = "매도";
            else
                tmpType = "매수";

            var transState = "<tr'>"
            transState += "<td>"+result.data[i].transaction_date +"</td>";
            transState += "<td>"+tmpType+"</td>";
            transState += "<td align='right'>"+comma(result.data[i].price)+"</td>";
            transState += "<td align='right'>"+result.data[i].units_traded+"</td>";
            transState += "<td align='right'>"+comma(result.data[i].total)+"</td>";
            transState += "</tr>";
                 //$('#buy').prepend(appendData);  
                 $('#transactions_history tr:first').after(transState); 
             }
             
         });
}


function getData_ETH(){
   var url = "https://api.bithumb.com/public/ticker";
   $.getJSON(url, function(result){ 
    var data = "";
    data = [result.data];

    var buy_price = "<tr'>"
    buy_price += "<td>"+comma(data[0].buy_price)+" | "+data[0].date+"</td>";
    buy_price += "<td>"+data[0].units_traded+"</td>";
    buy_price += "<td>누적수량(BTC)</td>";
    buy_price += "</tr>";
                 //$('#buy').prepend(appendData);

                 var sell_price = "<tr'>"
                 sell_price += "<td style='text-align: right;'>"+comma(data[0].sell_price)+" | "+data[0].date+"</td>";
                 sell_price += "<td>"+data[0].units_traded+"</td>";
                 sell_price += "<td>"+data[0].volume_7day+"</td>";
                 sell_price += "</tr>";
                 //$('#buy').prepend(appendData);
                 $('#table_buy tr:first').after(buy_price); 
                 $('#table_sell tr:first').after(sell_price); 
             });   
}


function getState_ETH(){
   var url = "https://api.bithumb.com/public/transaction_history";
   $.getJSON(url, function(result){ 
    var data = "";
    data =  result.data ;

    var tmpType="";

    for (i in result.data) {
               // alert(result.data[i].cont_no );
               if(result.data[i].type=="ask")
                tmpType = "매도";
            else
                tmpType = "매수";

            var transState = "<tr'>"
            transState += "<td>"+result.data[i].transaction_date +"</td>";
            transState += "<td>"+tmpType+"</td>";
            transState += "<td align='right'>"+comma(result.data[i].price)+"</td>";
            transState += "<td align='right'>"+result.data[i].units_traded+"</td>";
            transState += "<td align='right'>"+comma(result.data[i].total)+"</td>";
            transState += "</tr>";
                 //$('#buy').prepend(appendData);  
                 $('#transactions_history tr:first').after(transState); 
             }
             
         });
}

function mcc_buy() {


    var d = new Date();

    var now = d.getFullYear()+"-"+(d.getMonth() + 1) +"-"+ d.getDate() +" "+d.getHours()+":"+d.getMinutes()+":"+d.getSeconds();

    var mcc = Math.floor((Math.random() * 1000) + 1) ;
    var units_traded = Math.floor((Math.random() * 5) + 1) ;

    var buy_price = "<tr'>"
    buy_price += "<td>"+comma(mcc)+" | "+now+"</td>";
    buy_price += "<td align='right'>"+units_traded +"</td>";
                    buy_price += "<td  align='right'>"+units_traded +"</td>";//buy_price += "<td>누적수량(BTC)</td>";
                    buy_price += "</tr>";
                 //$('#buy').prepend(appendData);


                 //$('#buy').prepend(appendData);
                 $('#table_buy tr:first').after(buy_price); 

             }//mcc_buy


             function mcc_sell() { 
                var d = new Date(); 
                var now = d.getFullYear()+"-"+(d.getMonth() + 1) +"-"+ d.getDate() +" "+d.getHours()+":"+d.getMinutes()+":"+d.getSeconds();
                var mcc = Math.floor((Math.random() *1000) + 1);
                var units_traded = Math.floor((Math.random() * 5) + 1);

                var sell_price = "<tr'>";
                sell_price += "<td >"+comma(mcc)+" | "+now+"</td>";
                sell_price += "<td align='right' >"+units_traded+"</td>";
                    sell_price += "<td align='right'>"+units_traded+"</td>";//sell_price += "<td>누적수량(BTC)</td>";
                    sell_price += "</tr>";
                    //$('#buy').prepend(appendData);

                    $('#table_sell tr:first').after(sell_price); 
            }// mcc_sell() 

 function mcc_trading(coin) { 

    var table = ""; 
                var d = new Date(); 
                var now = d.getFullYear()+"-"+(d.getMonth() + 1) +"-"+ d.getDate() +" "+d.getHours()+":"+d.getMinutes()+":"+d.getSeconds();
                var mcc = Math.floor((Math.random() *1000) + 1);
                var units_traded = Math.floor((Math.random() * 5) + 1);

    var jURL ="trading_deal_list.php";
    var  param = {coin:coin};

    $.ajax({
        type: "POST",
        async: false,
        url: jURL,
        data:param,
        cache: false,
        //dataType: "json",
        success: function(data)
        { 
            var obj = $.parseJSON(data);

            var table = "";
            var money = "";
            var amount = "";var indata = "";


 $("#table_sell tr:first").after(""); 
  $("#table_buy tr:first").after(""); 
            for(var i in obj)
            { 

                if(obj[i].trading_kind == "S"){

                        table = "table_sell";
                        money = obj[i].trading_money;
                         amount = obj[i].trading_amount;
                 
                }else{

                        table = "table_buy";
                        money = obj[i].trading_money;
                        amount = obj[i].trading_amount;
                }


                indata = obj[i].trading_date.substr(0,10);

                sell_price = "<tr'>";
                sell_price += "<td ><b>"+comma(money) +"</b> ("+indata+")</td>";
                sell_price += "<td align='right' >"+comma(amount)+"</td>";
                    sell_price += "<td align='right'>"+comma(amount)+"</td>";//sell_price += "<td>누적수량(BTC)</td>";
                    sell_price += "</tr>";

 
                    $("#"+table+" tr:first").after(sell_price); 

            } 
        },
        error:function(e){
            alert("Data read 에러  ");
        }
    }); 




            }// mcc_sell() 

            function mcc_deal() 
            {
               var url = "trading_deal_list.php";

               $.getJSON(url, function(result){ 
                var data = "";
                data = [result.data];

                var buy_price = "<tr'>"
                buy_price += "<td>"+comma(data[0].buy_price)+" | "+data[0].date+"</td>";
                buy_price += "<td>"+data[0].units_traded+"</td>";
                buy_price += "<td>누적수량(BTC)</td>";
                buy_price += "</tr>";
                 //$('#buy').prepend(appendData);

                 var sell_price = "<tr'>"
                 sell_price += "<td >"+comma(data[0].sell_price)+" | "+data[0].date+"</td>";
                 sell_price += "<td align='right'>"+data[0].units_traded+"</td>";
                sell_price += "<td align='right'>"+data[0].units_traded+"</td>";//sell_price += "<td>누적수량(BTC)</td>";
                sell_price += "</tr>";
                 //$('#buy').prepend(appendData);
                 $('#table_buy tr:first').after(buy_price); 
                 $('#table_sell tr:first').after(sell_price); 
             });   

           }

           function comma(str) {
            str = String(str);
            return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
    }//function comma(str)



function memData(userID)
{

    var jURL ="getUserData.php";
    var  param = {userID:userID};

    $.ajax({
        type: "POST",
        async: false,
        url: jURL,
        data:param,
        cache: false,
        //dataType: "json",
        success: function(data)
        { 
            var obj = $.parseJSON(data);

            $("#myWallet").val(obj[0].myWallet);
            $("#myPOINT").val(obj[0].MyCash);
            $("#myMCC").val(obj[0].MCC);
            $("#myKRW").val(obj[0].currency);
 
            $("#buy_pos").val(obj[0].MyCash);
            $("#sell_pos").val(obj[0].MCC);

        },
        error:function(e){
            alert("Data read 에러  ");
        }
    }); 
}//func

/*
function trading_list()
{


    var jURL ="trading_deal_list.php";
    var  param = ""://{use_group:use_group};

    $.ajax({
        type: "POST",
        async: false,
        url: jURL,
        data:param,
        cache: false,
        //dataType: "json",
        success: function(data)
        {

            var obj = $.parseJSON(data);
            var tmpText = "";
            var deal_list = "";
  
            for(var i in obj)
            {

                coin_name
                 
                deal_list+="<tr id='tr_ord_"+obj[i].idx+"'>";
                deal_list+="<td><span class='view_state' >"+obj[i].idx+"</span></td>";
                deal_list+="<td>"+obj[i].trading_money+"</td>";
                deal_list+="<td>"+obj[i].trading_amount+"</td>";
                deal_list+="<td>"+obj[i].idx+"</td>";
                deal_list+="<td>";
                deal_list+="    <a href='#' id='order_"+obj[i].idx+"' class='btn btn-order btn-w-md btn-success btn-squared'>거래</a>";
                deal_list+="    <a href='#' id='ord_"+obj[i].idx+"' class='btn btn-cancle btn-w-md btn-success btn-squared'>취소</a></td>";
                deal_list+="</tr>";


            } 


              
                $('#deal_list tr:first').after(deal_list); 


        },
        error:function(e){
            alert(" lawData 에러  ");
        }
    });

}//func

 */