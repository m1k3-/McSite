function set_width_dm(parent_node){

    padding_total = 60;

    width_ = parent_node.width();
    $(".dynmap").each( function () {
        $(this).width((width_ - padding_total)/2);
    });
}

$(document).ready( function() {
    set_width_dm($("#body_content"));
});

$(window).resize(function () {
    set_width_dm($("#body_content"));
});