$(document).ready(function () {
    $('#btn_clear_cookie_events').click(function () {
        setCookie("guest_id", "", "s0");
        setCookie("guest_name", "", "s0");
        setCookie("guest_phone", "", "s0");
        setCookie("guest_major", "", "s0");
        setCookie("guest_event_message", "", "s0");
        window.location.reload();
    });
    $('#btn_clear_cookie_recruits').click(function () {
        setCookie("guest_id", "", "s0");
        setCookie("guest_name", "", "s0");
        setCookie("guest_phone", "", "s0");
        setCookie("guest_major", "", "s0");
        setCookie("guest_recruit_message", "", "s0");
        window.location.reload();
    });
});

function getsec(str) {
    var str1 = str.substring(1, str.length) * 1;
    var str2 = str.substring(0, 1);
    if (str2 == "s") {
        return str1 * 1000;
    } else if (str2 == "h") {
        return str1 * 60 * 60 * 1000;
    } else if (str2 == "d") {
        return str1 * 24 * 60 * 60 * 1000;
    }
}

function setCookie(name, value, time) {
    var strsec = getsec(time);
    var exp = new Date();
    exp.setTime(exp.getTime() + strsec * 1);
    document.cookie = name + "=" + value + ";expires=" + exp.toGMTString();
}
