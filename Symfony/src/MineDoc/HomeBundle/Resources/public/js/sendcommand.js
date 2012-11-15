function setday(elem) {
    $.ajax({
        url:$(elem).attr('href'),
        cache:false,
        dataType:'json',
        success:function (data) {
            if (data.ok) {
                $(elem).children().addClass("inactive");
                $(elem).next().html(data.time);
            }
        }
    });
    return false;
}

function earnmoney() {
    $.ajax({
        url:$('#earnmoney').attr('href'),
        cache:false,
        dataType:'json',
        success:function (data) {
            if (data.ok) {
                data.money = data.money + "$";
                $("#earnmoney input").addClass("inactive");
                $('#earnmoneytime').html(data.earnmoneytime);
                $('#money').html(data.money);
            }
        }
    });
    return false;
}

$(document).ready(function () {
    $('.command').click(function(){
        setday(this);
        return false;
    });
    $('#earnmoney').click(function(){
        earnmoney();
        return false;
    });
});