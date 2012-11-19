function command(elem) {
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

function command_util(elem) {
    $.ajax({
        url:$(elem).attr('href'),
        cache:false,
        dataType:'json',
        success:function (data) {
            if (data.money < 20) {
                $(this).children().addClass("inactive");
            }
            data.money = data.money + "$";
            $(elem).next().html(data.notice);
            $('#money').html(data.money);
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
        command(this);
        return false;
    });
    $('.command_util').click(function(){
        command_util(this);
        return false;
    });
    $('#earnmoney').click(function(){
        earnmoney();
        return false;
    });
});