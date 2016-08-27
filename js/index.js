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

function getsec(str) {
    var str1=str.substring(1,str.length)*1;
    var str2=str.substring(0,1);
    if (str2=="s") {
        return str1*1000;
    } else if (str2=="h") {
        return str1*60*60*1000;
    } else if (str2=="d") {
        return str1*24*60*60*1000;
    }
}

function setCookie(name,value,time) {
    var strsec = getsec(time);
    var exp = new Date();
    exp.setTime(exp.getTime() + strsec*1);
    document.cookie = name + "=" + value + ";expires=" + exp.toGMTString();
}