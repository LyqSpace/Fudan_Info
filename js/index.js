$(document).ready(function() {
    $('#fullpage').fullpage({
        loopHorizontal: false,
        controlArrows: false,
        scrollingSpeed: 300,
        anchors: ['m'],
        onSlideLeave: function(anchorLink, index, slideIndex, direction, nextSlideIndex){
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
        }
    });
    $('#btn_events').click(function() {
        $('#fullpage').fullpage.moveTo(1, 0);
    });
    $('#btn_recruits').click(function() {
        $('#fullpage').fullpage.moveTo(1, 1);
    });
    $('#btn_settings').click(function() {
        $('#fullpage').fullpage.moveTo(1, 2);
    });
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