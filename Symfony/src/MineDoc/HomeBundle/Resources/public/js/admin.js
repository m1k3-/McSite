/**
 * User: raimon_t
 * Date: 10/3/12
 * Time: 2:55 PM
 */

function changeMoney(elem) {
    money = $(elem).val();
    id = $(elem).attr("data-id");

    button = $(elem).next(".addmoney");

    button.attr("href", "/do/7/" + id + "/" + money);
}

function updateSearch(elem) {
    keywords = $.trim($(elem).val());
    if (keywords == "") {
        keywords = "nc";
    }
    button = $("#search_url");
    regex = new RegExp("/+[^/]+$");
    adress = button.attr('href');
    adress = adress.replace(regex, "/" + keywords);
    button.attr("href", adress);
}

$(document).ready(function () {
    $(".money_val").change(function(){
        changeMoney(this);
    });
    $("#search_panel").keyup(function(){
        updateSearch(this);
    });
});