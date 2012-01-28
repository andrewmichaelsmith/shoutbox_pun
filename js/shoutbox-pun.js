/**
 * shoutbox_pun
 *
 * https://github.com/andrewmichaelsmith/shoutbox_pun
 *
 *
 * Copyright (c) 2012 Andrew Smith
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 */

var timeout;
var waitTime = 1600;
var lastid = 0;
var done = false;
var myScroll;
var mobile = isMobile();
var counter = 0;


$("#loading").hide();


function getHTMLFromMessage(message)
{
	 var d = new Date(parseInt($("date", message).text()) * 1000);

     var hour = d.getHours();
     if (hour < 10) hour = "0" + hour;

     var min = d.getMinutes();
     if (min < 10) min = "0" + min;

     return "<li><b title=\"" + d.toDateString() + "\">[" + hour + ":" + min + "]</b> <b>" + $("username", message).text() + "</b>: " + $("text", message).text() + "</li>";
     
}

function addMessages(xml,append)
{
	
	if ($("iserror", xml).text() == "1")
	{
		$("#error").text(($("error", xml).text()))
	}
	else
	{
		$("#error").text("");
	}
	
	if ($("newones", xml).text() !== "0") {
        lastid = $("lastid", xml).text();

        var all = "";
        
        $("message", xml).each(function(id) {
            var message = $("message", xml).get(id);
            var html = getHTMLFromMessage(message);

            if(append === true)
            {
            	all = html + all;
            }
            else
            {
            	$("#shoutbox").prepend(html);
            }
            
            
        });
        
        if(append)
        {
            $("#shoutbox").append(all);
        }
        

        if (mobile) {
            myScroll.refresh();
        }
	}

    $("#loading").fadeOut();


    if (mobile && !done) {
        myScroll = new iScroll('sbox');
        done = true;
    }
    clearTimeout(timeout);
    timeout = setTimeout("updateShoutbox()", waitTime);

}


function addPage(pageNo)
{
	counter = pageNo;
	
	 $.get("extensions/shoutbox_pun/data.php", {
	        m: "list",
	        id: 0,
	        page: pageNo,
	        csrf_token: $('input[name="csrf_token"]').val()
	        
	    }, function(xml)
	    {

	        addMessages(xml,true);

	    });
	
}

function updateShoutbox() 
{

    $.get("extensions/shoutbox_pun/data.php", {
        m: "list",
        id: lastid,
        csrf_token: $('input[name="csrf_token"]').val()
    }, function(xml)
    {

        addMessages(xml,false);

    });

}

/*
 * This could be better but it works for now
 */

function isMobile() 
{
    if (navigator.userAgent.match(/Android/i) || navigator.userAgent.match(/webOS/i) || navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPod/i) || navigator.userAgent.match(/BlackBerry/)) {

        return true;

    }
    else 
    {
        return false;
    }
}

$(document).ready(function() {



    $("form#shoutform").submit(function() {


        $("#loading").fadeIn();

        clearTimeout(timeout);

        var sht = $("#shout").val();
        $("#shout").attr("value", "");
        $.get("extensions/shoutbox_pun/data.php", {
            add: sht,
            id: lastid,
            csrf_token: $('input[name="csrf_token"]').val()
        }, function(xml) {

            addMessages(xml,false);
        });

        return false;
    });

    updateShoutbox();

    setTimeout("updateShoutbox()", waitTime);
    
    $('#sbox').endlessScroll({
    	  fireOnce: true,
    	  ceaseFire: function(p)
    	  {
    		  return counter > 5;
    	  },
    
    	  callback: function(p){
    	    addPage(p);
    	  }
    	});

});