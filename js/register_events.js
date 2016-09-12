function dialog_disappear() {
    var dialog = document.getElementById('dialog');
    dialog.style.display = "none";
}

function change_event() {

    var select_obj = document.getElementById("select_event");
    if (select_obj == null) return;
    var select_idx = select_obj.selectedIndex;
    var event_obj = select_obj.options[select_idx];
    if (event_obj == null) return;
    var event_id = event_obj.value;

    var event_list = document.getElementById("event_list");
    var event_children = event_list.children;
    for (var i = 0; i < event_children.length; i++) {
        if (event_children[i].id == "event_id_" + event_id) {
            event_children[i].style.display = "block";
        } else {
            event_children[i].style.display = "none";
        }
    }
}

function change_date(this_obj, event_id) {

    if (this_obj == null) return;
    var date_id = this_obj.selectedOptions[0].value;

    var date_list = document.getElementsByName("registration_date_" + event_id);
    for (var i = 0; i < date_list.length; i++) {
        date_list[i].style.display = "none";
    }
    var date_item = document.getElementById("registration_serial_" + date_id);
    date_item.style.display = "block";
}

window.onload = function () {
    change_event();
    change_date();
};


function check_register() {

    var btn = document.getElementsByName('save')[0];
    btn.setAttribute("disabled", true);
    btn.className += " disabled";

    var error_message = "";
    var tmp_obj, tmp, info_list, i;

    var select_event_obj = document.getElementById('select_event');
    var select_event_id = select_event_obj.selectedOptions[0].value;
    var select_registration = document.getElementById('select_registration_' + select_event_id);
    var registration_serial = select_registration.selectedOptions[0].value;
    var registration_serial_list = document.getElementsByName('registration_serial');
    for (i = 0; i < registration_serial_list.length; i++) {
        registration_serial_list[i].value = registration_serial;
    }

    tmp_obj = document.getElementsByName("registration_id_tmp")[0];
    if (tmp_obj != null) {
        tmp = tmp_obj.value;
        if (tmp == "" || tmp == null) error_message += "学号不可为空<br>";
        info_list = document.getElementsByName("registration_id");
        for (i = 0; i < info_list.length; i++) {
            info_list[i].value = tmp;
        }
    } else error_message += "学号不可为空<br>";

    tmp_obj = document.getElementsByName("registration_name_tmp")[0];
    if (tmp_obj != null) {
        tmp = tmp_obj.value;
        if (tmp == "" || tmp == null) error_message += "姓名不可为空<br>";
        info_list = document.getElementsByName("registration_name");
        for (i = 0; i < info_list.length; i++) {
            info_list[i].value = tmp;
        }
    } else error_message += "姓名不可为空<br>";

    tmp_obj = document.getElementsByName("registration_major_tmp")[0];
    if (tmp_obj != null) {
        tmp = tmp_obj.value;
        if (tmp == "" || tmp == null) error_message += "专业不可为空<br>";
        info_list = document.getElementsByName("registration_major");
        for (i = 0; i < info_list.length; i++) {
            info_list[i].value = tmp;
        }
    } else error_message += "专业不可为空<br>";

    tmp_obj = document.getElementsByName("registration_phone_tmp")[0];
    if (tmp_obj != null) {
        tmp = tmp_obj.value;
        if (tmp == "" || tmp == null) error_message += "电话不可为空<br>";
        info_list = document.getElementsByName("registration_phone");
        for (i = 0; i < info_list.length; i++) {
            info_list[i].value = tmp;
        }
    } else error_message += "电话不可为空<br>";

    tmp_obj = document.getElementsByName("registration_message_tmp")[0];
    if (tmp_obj != null) {
        tmp = tmp_obj.value;
        info_list = document.getElementsByName("registration_message");
        for (i = 0; i < info_list.length; i++) {
            info_list[i].value = tmp;
        }
    }

    if (error_message == "") {
        var remember_obj = document.getElementById("remember_register_event_" + registration_serial);
        if (remember_obj.checked == true) {
            setCookie("guest_id", document.getElementsByName("registration_id_tmp")[0].value, "d360");
            setCookie("guest_name", document.getElementsByName("registration_name_tmp")[0].value, "d360");
            setCookie("guest_phone", document.getElementsByName("registration_phone_tmp")[0].value, "d360");
            setCookie("guest_major", document.getElementsByName("registration_major_tmp")[0].value, "d360");
            setCookie("guest_event_message", document.getElementsByName("registration_message_tmp")[0].value, "d360");
        } else {
            setCookie("guest_id", "", "s0");
            setCookie("guest_name", "", "s0");
            setCookie("guest_phone", "", "s0");
            setCookie("guest_major", "", "s0");
            setCookie("guest_event_message", "", "s0");
        }
        return true;
    } else {

        btn.className = btn.className.replace("disabled", "");
        btn.removeAttribute("disabled");

        var msg_box = document.getElementById("error_message_events");
        //var scrollTop = $('#page_guest_events').scrollTop();
        //var mask_top = 'style="top:' + scrollTop + 'px"';
        var dialog_top = 'style="top:' + ($(window).height() / 2 - 50) + 'px"';
        msg_box.innerHTML =
            '<div id="dialog">' +
            '   <div class="weui_mask"></div>' +
            '   <div class="weui_dialog"' + dialog_top + '>' +
            '       <div class="weui_dialog_hd">' +
            '           <strong class="weui_dialog_title">报名结果</strong>' +
            '      </div>' +
            '       <div class="weui_dialog_bd">' +
            error_message +
            '       </div>' +
            '       <div class="weui_dialog_ft">' +
            '           <a onclick="dialog_disappear();" class="weui_btn_dialog primary">确定</a>' +
            '       </div>' +
            '   </div>' +
            '</div>';

        return false;
    }
}

function check_register_forgotten() {
    var remember_obj = document.getElementById("remember");
    if (remember_obj.checked == true) {
        setCookie("guest_id", document.getElementsByName("registration_id")[0].value, "d360");
        setCookie("guest_name", document.getElementsByName("registration_name")[0].value, "d360");
        setCookie("guest_phone", document.getElementsByName("registration_phone")[0].value, "d360");
    } else {
        setCookie("guest_id", "", "s0");
        setCookie("guest_name", "", "s0");
        setCookie("guest_phone", "", "s0");
    }
    return true;
}

