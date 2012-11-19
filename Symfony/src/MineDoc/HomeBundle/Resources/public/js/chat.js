function reload_chat(){
    $.ajax({
        url: $("#link_getchat").attr('href'),
        cache: false,
        success: function(html){
            if (html != "") {
                $("#content_chat")[0].html(html);
                $("#content_chat")[0].scrollTo({bottom: '0'}, 500)
            }
        }
    });
}


function send_chat() {
    var clientmsg = $("#usermsg").val();
    if (clientmsg.length > 0) {
        $.post($("#submit_chat").attr("href"), {message:clientmsg});
        $("#usermsg").attr("value", "");
    }
}

$(document).ready( function() {
    reload_chat();
    setInterval(reload_chat, 1000);
    $("#usermsg").keyup(function (e) {
        if (e.keyCode == 13) {
            send_chat();
        }
    });
    $("#submit_chat").click(function(){
        send_chat();
        return false;
    });
    $("#big_chat").click(function () {
        $("#chat").toggleClass("big_chat");
        $("#body_content").toggleClass("little_body");
        if ($("#chat").hasClass("big_chat")) {
        $("#big_chat").text("-");
        }
        else {
            $("#big_chat").text("+");
        }
    });
});