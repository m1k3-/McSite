/**
 * User: raimon_t
 * Date: 10/3/12
 * Time: 2:55 PM
 */

$(document).ready(function () {

    $(".money_val").change(function() {

        money = $(this).val();
        id = $(this).attr("data-id")

        button = $(".addmoney")

        button.attr("href", "/do/7/" + id + "/" + money);
    });
});