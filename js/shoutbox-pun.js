var timeout;
var waitTime = 1600;
var lastid = 0;


$("#loading").hide();


function addMessages(xml) {

	console.log("called addmessages");
	
    if ($("newones", xml).text() !== "0")
    {
	    lastid = $("lastid", xml).text();
	
	    $("message", xml).each(function (id) {
	        var message = $("message", xml).get(id);
	        var d = new Date(parseInt($("date", message).text()) * 1000);
	
	        var hour = d.getHours();
	        if (hour < 10) hour = "0" + hour;
	
	        var min = d.getMinutes();
	        if (min < 10) min = "0" + min;
	
	
	        $("#shoutbox").prepend(
	
	        "<li><b title=\"" + d.toDateString() + "\">[" + hour + ":" + min + "]</b> <b>" + $("username", message).text() + "</b>: " + $("text", message).text() + "</li>");
	    });
    }
    
    $("#loading").fadeOut();
    
    clearTimeout(timeout);
    timeout = setTimeout("updateShoutbox()", waitTime);
}


function updateShoutbox() {

    $.get("/extensions/shoutbox_pun/data.php", {
        m: "list",
        id: lastid
    }, function (xml) {

    	addMessages(xml);

    });

}


$(document).ready(function () {

	

    $("form#shoutform").submit(function () {


        $("#loading").fadeIn();
        
        clearTimeout(timeout);

        $.get("/extensions/shoutbox_pun/data.php", {
            add: $("#shout").val(),
            id: lastid
        }, function (xml) {
        	
        	addMessages(xml);
        });
        $("#shout").attr("value", "");



        return false;
    });

    updateShoutbox();
    
    setTimeout("updateShoutbox()", waitTime);

});
