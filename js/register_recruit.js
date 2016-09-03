function change_clubs_category() {

    var select_obj = document.getElementById("select_clubs_category");
    if (select_obj == null) return;
    var select_idx = select_obj.selectedIndex;
    var category_obj = select_obj.options[select_idx];
    if (category_obj == null) return;
    var category_id = category_obj.value;

    var category_list = document.getElementById("clubs_category_list");
    var category_children = category_list.children;
    for (var i = 0; i < category_children.length; i++) {
        if (category_children[i].id == "clubs_category_" + category_id) {
            category_children[i].style.display = "block";
        } else {
            category_children[i].style.display = "none";
        }
    }
}

function change_club(this_obj, clubs_category_id) {

    if (this_obj == null) return;
    var club_id = this_obj.selectedOptions[0].value;

    var club_list = document.getElementsByName("club_category_" + clubs_category_id);
    for (var i = 0; i < club_list.length; i++) {
        club_list[i].style.display = "none";
    }
    var club_item = document.getElementById("club_" + club_id);
    club_item.style.display = "block";
}

window.onload = function () {
    change_clubs_category();
    change_club();
};


function check_regsiter_recruit() {

    var btn = document.getElementsByName('save')[0];
    btn.setAttribute("disabled", true);
    btn.className += " disabled";

    var error_message = "";
    var tmp_obj, tmp, info_list, i;

    var select_category_obj = document.getElementById('select_clubs_category');
    var select_category_id = select_category_obj.selectedOptions[0].value;
    var select_club_obj = document.getElementById('select_club_' + select_category_id);
    var club_name = select_club_obj.selectedOptions[0].value;
    var input_club_list = document.getElementsByName('username');
    for (i = 0; i < input_club_list.length; i++) {
        input_club_list[i].value = club_name;
    }

    tmp_obj = document.getElementsByName("register_recruit_id_tmp")[0];
    if (tmp_obj != null) {
        tmp = tmp_obj.value;
        if (tmp == "" || tmp == null) error_message += "学号不可为空<br>";
        info_list = document.getElementsByName("register_recruit_id");
        for (i = 0; i < info_list.length; i++) {
            info_list[i].value = tmp;
        }
    } else error_message += "学号不可为空<br>";

    tmp_obj = document.getElementsByName("register_recruit_name_tmp")[0];
    if (tmp_obj != null) {
        tmp = tmp_obj.value;
        if (tmp == "" || tmp == null) error_message += "姓名不可为空<br>";
        info_list = document.getElementsByName("register_recruit_name");
        for (i = 0; i < info_list.length; i++) {
            info_list[i].value = tmp;
        }
    } else error_message += "姓名不可为空<br>";

    tmp_obj = document.getElementsByName("register_recruit_major_tmp")[0];
    if (tmp_obj != null) {
        tmp = tmp_obj.value;
        if (tmp == "" || tmp == null) error_message += "专业不可为空<br>";
        info_list = document.getElementsByName("register_recruit_major");
        for (i = 0; i < info_list.length; i++) {
            info_list[i].value = tmp;
        }
    } else error_message += "专业不可为空<br>";

    tmp_obj = document.getElementsByName("register_recruit_phone_tmp")[0];
    if (tmp_obj != null) {
        tmp = tmp_obj.value;
        if (tmp == "" || tmp == null) error_message += "电话不可为空<br>";
        info_list = document.getElementsByName("register_recruit_phone");
        for (i = 0; i < info_list.length; i++) {
            info_list[i].value = tmp;
        }
    } else error_message += "电话不可为空<br>";

    tmp_obj = document.getElementsByName("register_recruit_message_tmp")[0];
    if (tmp_obj != null) {
        tmp = tmp_obj.value;
        info_list = document.getElementsByName("register_recruit_message");
        for (i = 0; i < info_list.length; i++) {
            info_list[i].value = tmp;
        }
    }

    if (error_message == "") {
        var remember_obj = document.getElementById("remember_register_recruit_" + club_name);
        if (remember_obj.checked == true) {
            setCookie("guest_id", document.getElementsByName("register_recruit_id_tmp")[0].value, "d360");
            setCookie("guest_name", document.getElementsByName("register_recruit_name_tmp")[0].value, "d360");
            setCookie("guest_phone", document.getElementsByName("register_recruit_phone_tmp")[0].value, "d360");
            setCookie("guest_major", document.getElementsByName("register_recruit_major_tmp")[0].value, "d360");
            setCookie("guest_recruit_message", document.getElementsByName("register_recruit_message_tmp")[0].value, "d360");
        } else {
            setCookie("guest_id", "", "s0");
            setCookie("guest_name", "", "s0");
            setCookie("guest_phone", "", "s0");
            setCookie("guest_major", "", "s0");
            setCookie("guest_recruit_message", "", "s0");
        }
        return true;

    } else {

        btn.className = btn.className.replace("disabled", "");
        btn.removeAttribute("disabled");

        var msg_box = document.getElementById("error_message_recruits");
        var scrollTop = $('#page_guest_recruits').scrollTop();
        var mask_top = 'style="top:' + scrollTop + 'px"';
        var dialog_top = 'style="top:' + (scrollTop + $(window).height() / 2 - 50) + 'px"';
        msg_box.innerHTML =
            '<div id="dialog">' +
            '   <div class="weui_mask"' + mask_top + '></div>' +
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

function check_recruit_history() {
    var remember_obj = document.getElementById("remember");
    if (remember_obj.checked == true) {
        setCookie("guest_id", document.getElementsByName("register_recruit_id")[0].value, "d360");
        setCookie("guest_name", document.getElementsByName("register_recruit_name")[0].value, "d360");
        setCookie("guest_phone", document.getElementsByName("register_recruit_phone")[0].value, "d360");
    } else {
        setCookie("guest_id", "", "s0");
        setCookie("guest_name", "", "s0");
        setCookie("guest_phone", "", "s0");
    }
    return true;
}