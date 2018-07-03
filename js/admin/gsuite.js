$(document).ready(function(){

    $('.gsuite').click(function(){
       alert('gsuite');
       return false;
    });
    
});

    function g2Click(etext){
       //event.preventDefault();
       //alert('gsuite2-0');
       alert(etext);
       alert(typeof(etext));
       //alert(etext.pathname);
       console.log(etext);
       //var n = etext.pathname.lastIndexOf("uname");
       var pattern = "uname/";
       //var n = etext.lastIndexOf("uname/");
       str2 = etext.substr(etext.indexOf(pattern)+pattern.length, etext.length);
       console.log("str2: "+str2);
       alert(str2);
       
        var xhr = new XMLHttpRequest();
        var self = this;

        // Wait for the AJAX call to complete, then update the
        // dummy element with the returned details
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4) {
                if (xhr.status >= 200) {
                    console.log(xhr.responseText);
                    var result = JSON.parse(xhr.responseText);
                    if (result) {
/*                         if (result.error == 0) {
                            // All OK - replace the dummy element.
                            resel.li.outerHTML = result.fullcontent;
                            if (self.Y.UA.gecko > 0) {
                                // Fix a Firefox bug which makes sites with a '~' in their wwwroot
                                // log the user out when clicking on the link (before refreshing the page).
                                resel.li.outerHTML = unescape(resel.li.outerHTML);
                            }
                            self.add_editing(result.elementid);
                            // Fire the content updated event.
                            require(['core/event', 'jquery'], function(event, $) {
                                event.notifyFilterContentUpdated($(result.fullcontent));
                            });
                        } else {
                            // Error - remove the dummy element
                            resel.parent.removeChild(resel.li);
                            new M.core.alert({message: result.error});
                        } */
                    }
                } else {
//                    new M.core.alert({message: M.util.get_string('servererror', 'moodle')});
                }
            }
        };

        // Prepare the data to send
        var searchem = encodeURI('email:'+str2+'@tdmu.edu.ua');
        alert(searchem);
        var formData = new FormData();
        formData.append('domain', 'tdmu.edu.ua');
        formData.append('query', searchem);
console.log(formData);
        // Send the AJAX call
        xhr.open("GET", "https://www.googleapis.com/admin/directory/v1/users", true);
        xhr.send(formData);
        
       return false;
    }