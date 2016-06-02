function change_event() {

    var select_obj = document.getElementById("select_event");
    if (select_obj == null) return;
    var select_idx = select_obj.selectedIndex;
    var event_id = select_obj.options[select_idx].value;

    var event_list = document.getElementById("event_list");
    var event_children = event_list.children;
    for (var i = 0; i < event_children.length; i++) {
        if (event_children[i].id == "event_id_" + event_id) {
            event_children[i].style.display="block";
        } else {
            event_children[i].style.display="none";
        }
    }
}

function change_date() {

    var select_obj = document.getElementById("select_date");
    if (select_obj == null) return;
    var select_idx = select_obj.selectedIndex;
    var date_id = select_obj.options[select_idx].value;

    var date_list = document.getElementById("date_list");
    var date_children = date_list.children;
    for (var i = 0; i < date_children.length; i++) {
        if (date_children[i].id == "registration_serial_" + date_id) {
            date_children[i].style.display="block";
        } else {
            date_children[i].style.display="none";
        }
    }
}

window.onload = function() {
    change_event();
    change_date();
}