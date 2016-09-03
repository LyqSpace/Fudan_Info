$(document).ready(function () {

    mp.onLeave(function (slideIndex, nextSlideIndex) {
        switch (slideIndex) {
            case 0:
                $('#btn_guest_events').removeClass('weui_bar_item_on');
                break;
            case 1:
                $('#btn_guest_recruits').removeClass('weui_bar_item_on');
                break;
            case 2:
                $('#btn_guest_reviews').removeClass('weui_bar_item_on');
                break;
        }
        switch (nextSlideIndex) {
            case 0:
                $('#btn_guest_events').addClass('weui_bar_item_on');
                break;
            case 1:
                $('#btn_guest_recruits').addClass('weui_bar_item_on');
                break;
            case 2:
                $('#btn_guest_reviews').addClass('weui_bar_item_on');
                break;
        }
    });

    ['#btn_guest_events', '#btn_guest_recruits', '#btn_guest_reviews'].forEach(function (name, index) {
        $(name).click(function () {
            mp.moveTo(index)
        })
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
