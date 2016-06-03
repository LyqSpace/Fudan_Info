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
};

function check_register() {

    var tmp_obj, tmp, info_list, i;
    tmp_obj = document.getElementsByName("registration_id_tmp")[0];
    if (tmp_obj != null) {
        tmp = tmp_obj.value;
        info_list = document.getElementsByName("registration_id");
        for (i = 0; i < info_list.length; i++) {
            info_list[i].value = tmp;
        }
    }

    tmp_obj = document.getElementsByName("registration_name_tmp")[0];
    if (tmp_obj != null) {
        tmp = tmp_obj.value;
        info_list = document.getElementsByName("registration_name");
        for (i = 0; i < info_list.length; i++) {
            info_list[i].value = tmp;
        }
    }

    tmp_obj = document.getElementsByName("registration_major_tmp")[0];
    if (tmp_obj != null) {
        tmp = tmp_obj.value;
        info_list = document.getElementsByName("registration_major");
        for (i = 0; i < info_list.length; i++) {
            info_list[i].value = tmp;
        }
    }

    tmp_obj = document.getElementsByName("registration_phone_tmp")[0];
    if (tmp_obj != null) {
        tmp = tmp_obj.value;
        info_list = document.getElementsByName("registration_phone");
        for (i = 0; i < info_list.length; i++) {
            info_list[i].value = tmp;
        }
    }

    return true;
}