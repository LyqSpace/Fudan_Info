$(document).ready(function() {
    $('#fullpage').fullpage({
        loopHorizontal: false,
        controlArrows: false,
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