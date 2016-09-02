$(document).ready(function () {

    $('#btn_events').click(function () {
        mp.moveTo(0);
    });
    $('#btn_recruits').click(function () {
        mp.moveTo(1);
    });
    $('#btn_settings').click(function () {
        mp.moveTo(2);
    });

    mp.onLeave(function (slideIndex, nextSlideIndex) {
        switch (slideIndex) {
            case 0:
                $('#btn_events').removeClass('weui_bar_item_on');
                break;
            case 1:
                $('#btn_recruits').removeClass('weui_bar_item_on');
                break;
            case 2:
                $('#btn_settings').removeClass('weui_bar_item_on');
                break;
        }
        switch (nextSlideIndex) {
            case 0:
                $('#btn_events').addClass('weui_bar_item_on');
                break;
            case 1:
                $('#btn_recruits').addClass('weui_bar_item_on');
                break;
            case 2:
                $('#btn_settings').addClass('weui_bar_item_on');
                break;
        }
    });

    mp.init();
});

function count(text_id, cnt_id, cnt_limit) {
    var textarea = document.getElementById(text_id);
    var str = textarea.value;
    var len = 0;
    var new_str = "";
    for (var i = 0; i < str.length; i++) {
        if (str.charCodeAt(i) > 255) {
            len += 2;
        } else {
            len += 1;
        }
        if (len > cnt_limit) {
            if (str.charCodeAt(i) > 255) {
                len -= 2;
            } else {
                len -= 1;
            }
            break;
        }
        new_str += str.charAt(i);
    }
    textarea.value = new_str;
    cnt_id.innerHTML = len;
}
