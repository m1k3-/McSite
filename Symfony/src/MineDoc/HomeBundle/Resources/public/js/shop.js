/**
 * User: raimon_t
 * Date: 10/3/12
 * Time: 2:55 PM
 */

$(document).ready(function () {
    $(".tab").click(function() {

        $(".tab.active").removeClass('active');

        $(this).addClass('active');

        $(".item").each(function () {
            $(this).css('display', 'none');
        });

        if ($(this).attr('data-category') == "all"){
            $(".item").each(function () {
                $(this).css('display', 'block');
            });
        }
        else {
            $(".item." + $(this).attr('data-category')).each(function () {
                $(this).css('display', 'block');
            });
        }
    });

    $(".price").change(function() {

        price = $(this).attr('data-price');
        mult = $(this).val();

        $("span", $(this).parent().prev()).html((price * mult).toFixed(2));

        link_node = $(".choice", $(this).parent().parent());

        link_node.attr("href", "/buy/" + link_node.attr("data-id") + "/" + mult);
    });
});