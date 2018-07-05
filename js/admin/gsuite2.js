    function g22Click(etext){
       alert(etext);
       var pattern = "uname/";
       //var n = etext.lastIndexOf("uname/");
       str2 = etext.substr(etext.indexOf(pattern)+pattern.length, etext.length);
       console.log("str2: "+str2);
       alert(str2);
$.ajax({
	   url:etext,
	   type:"POST",            
	   //data:"catid="+$("#Cat_id option:selected").val(),
	   //dataType:"json",
	   "success":function(data){                
         console.log(data);
	         if(data==null){
	              //$("#product_type").empty();    
                  alert(data);
	         }else{
                 alert(data);
	              //var obj = eval(data);
	              //$("#product_type").empty();
	              //$.each(obj, function(key, value) {
	              //   $("#product_type").append("<option value="+key+">"+value+"</option>");
	              //});
	                     
	         }
	       }
	      });       
        return false;
    }