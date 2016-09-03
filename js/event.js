function show_details() {
    var details_form = document.getElementById('details_form');
    var notification_check = document.getElementsByName('notification')[0];
    if (notification_check == null || details_form == null) return;
    if (notification_check.checked) {
        details_form.style.display = "block";
    } else {
        details_form.style.display = "none";
    }
}

function show_register_form() {
    var register_form = document.getElementById("register_form");
    var date_type = document.getElementsByName("date_type")[0];
    if (date_type == null || register_form == null) return;
    if (date_type.value == "date_st") {
        register_form.style.display = "block";
    } else {
        document.getElementsByName('register')[0].checked = null;
        register_form.style.display = "none";
    }
    show_register_date();
}

function show_register_date() {
    var register_form = document.getElementById('register_date_form');
    var register_check = document.getElementsByName('register')[0];
    if (register_check == null || register_form == null) return;
    if (register_check.checked) {
        register_form.style.display = "block";
    } else {
        register_form.style.display = "none";
        document.getElementsByName('register_date')[0].value = null;
    }
    show_register_type();
}

function show_register_type() {

    var this_obj = document.getElementsByName('register_date_type')[0];
    if (this_obj == null) return;
    var date_type = this_obj.selectedOptions[0].value;

    if (date_type == 'date_st') {
        var date_type_st = document.getElementById('register_date_type_st');
        if (date_type_st != null) {
            date_type_st.style.display = 'block';
        }
        var date_type_ed = document.getElementById('register_date_type_ed');
        if (date_type_ed != null) {
            date_type_ed.style.display = 'none';
        }
    } else {
        var date_type_st = document.getElementById('register_date_type_st');
        if (date_type_st != null) {
            date_type_st.style.display = 'none';
        }
        var date_type_ed = document.getElementById('register_date_type_ed');
        if (date_type_ed != null) {
            date_type_ed.style.display = 'block';
        }
    }
}

function add_default_time(date_form_name) {
    var date = new Date();
    var year = date.getFullYear();
    var month = date.getMonth() + 1;
    var day = date.getDate();

    if (month >= 1 && month <= 9) {
        month = "0" + month;
    }
    if (day >= 0 && day <= 9) {
        day = "0" + day;
    }
    var date_form = document.getElementsByName(date_form_name)[0];
    if (date_form == null || date_form.value != "") return;
    date_form.value = year + "-" + month + "-" + day + "T18:30";
}

window.onload = function () {
    show_details();
    show_register_form();
    show_register_date();
    show_register_type();
};

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

function dialog_disappear() {
    var dialog = document.getElementById('dialog');
    dialog.style.display = "none";
}

function check_event(btn) {

    btn.setAttribute("disabled", true);
    btn.className += " disabled";

    var error_message = "";

    var date = document.getElementsByName("date")[0].value;
    var register_date = document.getElementsByName("register_date")[0].value;
    if (date != "" && register_date != "") {
        if (date <= register_date) error_message += "报名时间不可晚于活动时间<br>";
    }
    var register_check = document.getElementsByName("register")[0];
    var register_type = document.getElementsByName("register_date_type")[0].value;

    if (register_check.checked == true && register_date == "" && register_type == "date_ed") error_message += "报名截止时间不可为空<br>";
    if (register_check.checked == false && register_date != "") error_message += "报名时间不为空，请勾选需要提前报名<br>";

    var str = document.getElementById("propa_url").value;
    var propa_url_len = 0;
    for (var i = 0; i < str.length; i++) {
        if (str.charCodeAt(i) > 255) {
            error_message += "软文网址只粘贴软文链接，请不要写入其它汉字内容<br>";
            break;
        } else {
            propa_url_len++;
        }
    }
    if (propa_url_len > 600) error_message += "软文网址超过字数限制<br>";

    var detail_len = document.getElementsByName("details")[0].value.length;
    var check_ele = document.getElementsByName("notification");
    if (check_ele[0].checked == true && detail_len == 0 && propa_url_len == 0) error_message += "详细描述或软文网址不可为空<br>";
    if (check_ele[0].checked == false && (detail_len > 0 || propa_url_len > 0)) error_message += "详细描述或软文网址不为空，请勾选有详细描述<br>";

    if (error_message == "") {
        return true;
    } else {

        btn.className = btn.className.replace("disabled", "");
        btn.removeAttribute("disabled");

        var msg_box = document.getElementById("error_message");
        msg_box.innerHTML =
            '<div id="dialog" >' +
            '   <div class="weui_mask"></div>' +
            '   <div class="weui_dialog">' +
            '       <div class="weui_dialog_hd">' +
            '           <strong class="weui_dialog_title">保存结果</strong>' +
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

function reedit_event(submited) {

    var form = document.getElementsByName("edit_event")[0];
    if (submited == "save" || submited == "save_as") {
        form.action = "save_event.php";
        if (submited == "save_as") {
            document.getElementsByName("event_id")[0].value = null;
        }
        var btn = document.getElementsByName(submited)[0];
        return check_event(btn);
    } else if (submited == "delete") {
        delete_event();
        return false;
    }
    return false;
}

function confirm_delete_event() {
    var form = document.getElementsByName("edit_event")[0];
    form.action = "delete_event.php";
    form.submit();
}

function delete_event() {

    var btn = document.getElementsByName("delete")[0];
    btn.setAttribute("disabled", true);
    btn.className += " disabled";

    var confirm_box = document.getElementById("confirm_message");
    confirm_box.innerHTML =
        '<div id="dialog">' +
        '   <div class="weui_mask"></div>' +
        '   <div class="weui_dialog">' +
        '       <div class="weui_dialog_hd">' +
        '           <strong class="weui_dialog_title">删除确认</strong>' +
        '       </div>' +
        '       <div class="weui_dialog_bd">警告!删除操作不可逆!请再次确认是否删除该则活动!</div>' +
        '       <div class="weui_dialog_ft">' +
        '           <a onclick="dialog_disappear();" class="weui_btn_dialog primary">取消</a>' +
        '           <a onclick="confirm_delete_event();" class="weui_btn_dialog default">确定</a>' +
        '       </div>' +
        '   </div>' +
        '</div>';

    btn.className = btn.className.replace("disabled", "");
    btn.removeAttribute("disabled");

    return false;
}

function check_review() {

    var btn = document.getElementsByName("save")[0];
    btn.setAttribute("disabled", true);
    btn.className += " disabled";

    var str = document.getElementById("review_url").value;
    var error_message = "";
    for (var i = 0; i < str.length; i++) {
        if (str.charCodeAt(i) > 255) {
            error_message += "此处只粘贴回顾链接，请不要写入其它汉字内容<br>";
            break;
        }
    }

    if (error_message === "") {
        return true;
    } else {
        var msg_box = document.getElementById("error_message");
        msg_box.innerHTML =
            '<div id="dialog">' +
            '   <div class="weui_mask"></div>' +
            '   <div class="weui_dialog">' +
            '       <div class="weui_dialog_hd">' +
            '           <strong class="weui_dialog_title">保存结果</strong>' +
            '      </div>' +
            '       <div class="weui_dialog_bd">' +
            error_message +
            '       </div>' +
            '       <div class="weui_dialog_ft">' +
            '           <a onclick="dialog_disappear();" class="weui_btn_dialog primary">确定</a>' +
            '       </div>' +
            '   </div>' +
            '</div>';

        btn.className = btn.className.replace("disabled", "");
        btn.removeAttribute("disabled");

        return false;
    }

}
