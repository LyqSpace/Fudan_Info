$(document).ready(function() {
    $('#fullpage').fullpage({
        loopHorizontal: false,
        controlArrows: false,
        keyboardScrolling: false,
        touchSensitivity: -1
    });
    $('#btn_events').click(function() {
        $('#fullpage').fullpage.moveTo(1, 0);
        $('#btn_events').addClass('weui_bar_item_on');
        $('#btn_recruits').removeClass('weui_bar_item_on');
        $('#btn_settings').removeClass('weui_bar_item_on');
    });
    $('#btn_recruits').click(function() {
        $('#fullpage').fullpage.moveTo(1, 1);
        $('#btn_events').removeClass('weui_bar_item_on');
        $('#btn_recruits').addClass('weui_bar_item_on');
        $('#btn_settings').removeClass('weui_bar_item_on');
    });
    $('#btn_settings').click(function() {
        $('#fullpage').fullpage.moveTo(1, 2);
        $('#btn_events').removeClass('weui_bar_item_on');
        $('#btn_recruits').removeClass('weui_bar_item_on');
        $('#btn_settings').addClass('weui_bar_item_on');
    });
});