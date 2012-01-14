var timeout;
var waitTime = 1600;
var lastid = 0;
var done = false;
var myScroll;
var mobile = isMobile();


$("#loading").hide();


function addMessages(xml) {

    if ($("newones", xml).text() !== "0") {
        lastid = $("lastid", xml).text();

        $("message", xml).each(function(id) {
            var message = $("message", xml).get(id);
            var d = new Date(parseInt($("date", message).text()) * 1000);

            var hour = d.getHours();
            if (hour < 10) hour = "0" + hour;

            var min = d.getMinutes();
            if (min < 10) min = "0" + min;


            $("#shoutbox").prepend(

            "<li><b title=\"" + d.toDateString() + "\">[" + hour + ":" + min + "]</b> <b>" + $("username", message).text() + "</b>: " + $("text", message).text() + "</li>");
        });

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


function updateShoutbox() {

    $.get("/extensions/shoutbox_pun/data.php", {
        m: "list",
        id: lastid
    }, function(xml) {

        addMessages(xml);

    });

}

/*
 * This could be better but it works for now
 */

function isMobile() {
    if (navigator.userAgent.match(/Android/i) || navigator.userAgent.match(/webOS/i) || navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPod/i) || navigator.userAgent.match(/BlackBerry/)) {

        return true;

    }
    else {
        return false;
    }
}

$(document).ready(function() {



    $("form#shoutform").submit(function() {


        $("#loading").fadeIn();

        clearTimeout(timeout);

        var sht = $("#shout").val();
        $("#shout").attr("value", "");
        $.get("/extensions/shoutbox_pun/data.php", {
            add: sht,
            id: lastid
        }, function(xml) {

            addMessages(xml);
        });

        return false;
    });

    updateShoutbox();

    setTimeout("updateShoutbox()", waitTime);

});