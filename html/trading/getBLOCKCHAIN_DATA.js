


function getBLOCKCHAIN_DATA(trading,state){
 
   //var url = "https://api.bithumb.com/public/transaction_history";
   //var url = "https://api.coinone.co.kr/ticker?currency="+state;

 //vcvar url = "https://api.coinone.co.kr/ticker?currency="+state;
 var jURL = "ticker.php" ;
 
  var  param = {trading:trading,currency:state};

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

      alert(data);
      $("#buy_KRW").val(obj[0].low);
      $("#sell_KRW").val(obj[0].high); 

      $("#curentAccount").val(obj[0].high);


      $("#buy_KRW").val(obj[0].high);
      $("#sell_KRW").val(obj[0].high); 
  

    },
    error:function(e){
      alert("Data read 에러  ");
    }
  });
}
  

function comma(str) {
  str = String(str);
  return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
}//function comma(str)
