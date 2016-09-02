$(document).ready(function() {
     mp.onLeave(function(slideIndex, nextSlideIndex){
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

    ['#btn_events', '#btn_recruits', '#btn_settings'].forEach(function (name, index) {
       $(name).click(function () { mp.moveTo(index) })
    });

    mp.init();
});

function logout() {
    var d = new Date();
    d.setTime(d.getTime()-1);
    document.cookie = "login_serial=; expires=" + d.toUTCString();
    window.location.href = "login.html";
}
