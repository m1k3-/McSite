function set_width_dm_(node, node1){
    width1 = node1.width();
    width = node.width();
    node.css("margin-left", (width1 - width) / 2);
}

function size_body(){
    body_c = $("#body_content");
    body_c.css("height", parseInt($("#body").css("height")) - (body_c.position().top + 40));
}

$(document).ready( function() {
    body = $("#body");
    header = $("#head_img");
    set_width_dm_(header, body);
    header.css("display", 'inline');
    size_body();
});

$(window).resize(function () {
    body = $("#body");
    set_width_dm_($("#head_img"), body);
    size_body();
});