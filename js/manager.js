$(document).ready(function() {
    $('#fullpage').fullpage({
        loopHorizontal: false,
        controlArrows: false
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