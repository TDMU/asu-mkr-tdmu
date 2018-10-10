    function getGoogleInfoClick(etext){
        //alert(etext);
        var pattern = "uname/";
        str2 = etext.substr(etext.indexOf(pattern)+pattern.length, etext.length);
        //console.log("str2: "+str2);
        $.ajax({
            url:etext,
            type:"POST",
            "success":function(data){
                    //console.log(data);
                    $("#gsuiteinfo").html(data);
                    $("#gSuiteInfoModal").modal();
            },
            "error":function(response){
                    //console.log(data);
                    $("#gsuiteinfo").html(response.responseText);
                    $("#gSuiteInfoModal").modal();
            }
	    });       
        return false;
    }