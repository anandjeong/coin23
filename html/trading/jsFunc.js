function getMyBalance(wallet) 
{
 var myBalance = 1000000;
 /* $.post( "getMyBalance.php", { wallet: wallet })
            .done(function( data ) {
                alert( "내 작고확인 : 1,000,000 " + data );
                myBalance = 1000000;
                $('#modal_myBalance').html(myBalance); 
 
            });//$.post*/

$('#modal_myBalance').html(comma(myBalance)); 
 
     
}

function trading_list(loginState,loginID) 
{


    var jURL ="trading_deal_list.php";
    var  param =  {dealstate: 0};

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
            for(var i in obj)
            {
 

                if(obj[i].trading_state =="B")
                    tmpText = "매도";
                else    if(obj[i].trading_state =="S")
                    tmpText = "매수";

                deal_list+="<tr id='tr_ord_"+obj[i].idx+"'>";
                deal_list+="    <td><span class='view_state' >"+obj[i].coin_name+"</span></td>";
                deal_list+="    <td>"+tmpText+"</td>";
                deal_list+="    <td style='text-align: right;'>"+comma(obj[i].trading_money)+"</td>";
                deal_list+="    <td style='text-align: right;'>"+comma(obj[i].trading_amount)+"</td>";
                
                deal_list+="    <td>";
               /// if(loginState)
               /// {
                    deal_list+="        <a href='#' id='order_"+obj[i].idx+"_"+obj[i].coin_name+"_"+obj[i].trading_money+"_"+obj[i].trading_amount+"' class='btn btn-order btn-w-md btn-success btn-squared'>거래</a>";
                    
                    if(loginID == registerID)
                    {
                        deal_list+="        <a href='#' id='ord_"+obj[i].idx+"' class='btn btn-cancle btn-w-md btn-success btn-squared'>취소</a>";
                    }
                ///}
                deal_list+="    <td>";
                deal_list+="</tr>";


            } 



            $('#deal_list tr:first').after(deal_list); 


        },
        error:function(e){
            alert(" lawData 에러  ");
        }
    });

}//func


function trading_deal_list()
{


    var jURL ="trading_deal_list.php";
    var  param =  {dealstate: 1};

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
                if(obj[i].trading_state =="B")
                    tmpText = "매도";
                else    if(obj[i].trading_state =="S")
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



            $('#transactions_history tr:first').after(deal_list); 


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
/*document.write('현재 년: ' + d.getFullYear() + '<br />');
document.write('현재 월: ' + (d.getMonth() + 1) + '<br />');
document.write('현재 일: ' + d.getDate() + '<br />');

document.write('<br />'); // 줄바꿈

document.write('현재 시: ' + d.getHours() + '<br />');
document.write('현재 분: ' + d.getMinutes() + '<br />');
document.write('현재 초: ' + d.getSeconds() + '<br />');

document.write('<br />');

document.write('오늘 요일: ' + d.getDay() + '<br />'); // 일요일 = 0
*/


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

             }


             function mcc_sell() {


                var d = new Date();

                var now = d.getFullYear()+"-"+(d.getMonth() + 1) +"-"+ d.getDate() +" "+d.getHours()+":"+d.getMinutes()+":"+d.getSeconds();
/*document.write('현재 년: ' + d.getFullYear() + '<br />');
document.write('현재 월: ' + (d.getMonth() + 1) + '<br />');
document.write('현재 일: ' + d.getDate() + '<br />');

document.write('<br />'); // 줄바꿈

document.write('현재 시: ' + d.getHours() + '<br />');
document.write('현재 분: ' + d.getMinutes() + '<br />');
document.write('현재 초: ' + d.getSeconds() + '<br />');

document.write('<br />');

document.write('오늘 요일: ' + d.getDay() + '<br />'); // 일요일 = 0
*/


var mcc = Math.floor((Math.random() *1000) + 1) ;
var units_traded = Math.floor((Math.random() * 5) + 1) ;



var sell_price = "<tr'>"
sell_price += "<td >"+comma(mcc)+" | "+now+"</td>";
sell_price += "<td align='right' >"+units_traded+"</td>";
                sell_price += "<td align='right'>"+units_traded+"</td>";//sell_price += "<td>누적수량(BTC)</td>";
                sell_price += "</tr>";
                 //$('#buy').prepend(appendData);

                 $('#table_sell tr:first').after(sell_price); 
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