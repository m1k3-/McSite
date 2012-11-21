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
    keywords = $(elem).val();
    button = $("#search_url");
    regex = "/+([^/]*$)";
    adress = button.attr('href');
    adress = adress.replace("regex", "/search/" + keywords);
    button.attr("href", adress);
}

$(document).ready(function () {
    $(".money_val").change(function(){
        changeMoney(this);
    });
    $("#search_panel").change(function(){
        updateSearch(this);
    });
});