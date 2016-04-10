function logout() {
    var d = new Date();
    d.setTime(d.getTime()-1);
    document.cookie = "login_serial=; expires=" + d.toUTCString();
    window.location.href = "index.php";
}

function show_details() {
    details_form = document.getElementById('details_form');
    notification_check = document.getElementsByName('notification')[0];
    if (notification_check == null) return;
    if (notification_check.checked) {
        details_form.style.display = "block";
    } else {
        details_form.style.display = "none";
    }
}

function show_register_form() {
    var register_form = document.getElementById("register_form");
    var date_type = document.getElementsByName("date_type")[0];
    if (date_type == null) return;
    if (date_type.value == "date_st") {
        register_form.style.display = "block";
    } else {
        document.getElementsByName('register')[0].checked = null;
        show_register_date();
        register_form.style.display = "none";
    }
}

function show_register_date() {
    register_form = document.getElementById('register_date_form');
    register_check = document.getElementsByName('register')[0];
    if (register_check == null) return;
    if (register_check.checked) {
        register_form.style.display = "block";
    } else {
        register_form.style.display = "none";
        document.getElementsByName('register_date')[0].value = null;
    }
}

window.onload = function () {
    show_details();
    show_register_form();
    show_register_date();
}

function random_item_color() {
    var color_arr = [
        "rgb(240, 227, 249)", "rgb(240, 227, 249)", "rgb(227, 255, 164)", "rgb(202, 244, 217)",
        "rgb(226, 237, 209)", "rgb(255, 234, 223)", "rgb(199, 220, 167)", "rgb(254, 177, 134)",
        "rgb(254, 214, 191)", "rgb(250, 198, 203)", "rgb(254, 197, 165)", "rgb(229, 187, 204)"];
    var last_color_id_0 = -1;
    var last_color_id_1 = -1;
    for (var i = 0; i < 10000; i++) {
        var item_id = "review_item" + String(i);
        var item_obj = document.getElementById(item_id);
        if (item_obj == null) break;
        var color_id = Math.floor(Math.random() * color_arr.length);
        while (color_id == last_color_id_0 || color_id == last_color_id_1) {
            var color_id = Math.floor(Math.random() * color_arr.length);
        }
        last_color_id_0 = last_color_id_1;
        last_color_id_1 = color_id;
        item_obj.style.background = color_arr[color_id];
    }
}

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
    var str = document.getElementById("title").value;
    var len = 0;
    for (var i = 0; i < str.length; i++) {
        if (str.charCodeAt(i) > 255) {
            len += 2;
        } else {
            len += 1;
        }
    }
    if (len > 70) error_message += "标题超过字数限制<br>";
    if (len == 0) error_message += "标题不可为空<br>";

    var str = document.getElementById("speaker").value;
    var len = 0;
    for (var i = 0; i < str.length; i++) {
        if (str.charCodeAt(i) > 255) {
            len += 2;
        } else {
            len += 1;
        }
    }
    if (len > 50) error_message += "主讲人介绍超过字数限制<br>";

    var str = document.getElementById("location").value;
    var len = 0;
    for (var i = 0; i < str.length; i++) {
        if (str.charCodeAt(i) > 255) {
            len += 2;
        } else {
            len += 1;
        }
    }
    if (len > 40) error_message += "地点超过字数限制<br>";
    if (len == 0) error_message += "地点不可为空<br>";

    var date = document.getElementsByName("date")[0].value;
    if (date == "") error_message += "活动时间不可为空<br>";

    var check_ele = document.getElementsByName("category");
    if (check_ele[0].value == "") error_message += "类别不可为空<br>";

    var register_date = document.getElementsByName("register_date")[0].value;
    if (date != "" && register_date != "") {
        if (date <= register_date) error_message += "报名时间不可晚于活动时间<br>";
    }
    var register_check = document.getElementsByName("register")[0];
    var date_type = document.getElementsByName("date_type")[0].value;
    if (date_type == "date_ed" && register_check.checked == true) error_message += "选了截止时间不可再填报名时间<br>";
    if (register_check.checked == true && register_date == "") error_message += "报名时间不可为空<br>";
    if (register_check.checked == false && register_date != "") error_message += "报名时间不为空，请勾选需要提前报名<br>";

    var str = document.getElementById("details_text").value;
    var detail_len = 0;
    for (var i = 0; i < str.length; i++) {
        if (str.charCodeAt(i) > 255) {
            detail_len += 2;
        } else {
            detail_len += 1;
        }
    }
    if (detail_len > 300) error_message += "详细描述超过字数限制<br>";

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
    if (propa_url_len > 300) error_message += "软文网址超过字数限制<br>";

    var check_ele = document.getElementsByName("notification");
    if (check_ele[0].checked == true && detail_len == 0 && propa_url_len == 0) error_message += "详细描述或软文网址不可为空<br>";
    if (check_ele[0].checked == false && (detail_len > 0 || propa_url_len > 0)) error_message += "详细描述或软文网址不为空，请勾选有详细描述<br>";

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
        '           <a onclick="dialog_disappear();" class="weui_btn_dialog default">取消</a>' +
        '           <a onclick="confirm_delete_event();" class="weui_btn_dialog primary">确定</a>' +
        '       </div>' +
        '   </div>' +
        '</div>';

    btn.className = btn.className.replace("disabled", "");
    btn.removeAttribute("disabled");

    return false;
}

function check_recruit() {

    var btn = document.getElementsByName("save")[0];
    btn.setAttribute("disabled", true);
    btn.className += " disabled";

    var error_message = "";

    var str = document.getElementById("details_text").value;
    var len = 0;
    for (var i = 0; i < str.length; i++) {
        if (str.charCodeAt(i) > 255) {
            len += 2;
        } else {
            len += 1;
        }
    }
    if (len > 300) error_message += "招新内容超过字数限制<br>";
    if (len == 0) error_message += "招新内容不可为空<br>";

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

function reedit_recruit(submited) {

    var form = document.getElementsByName("edit_recruit")[0];
    if (submited == "save") {
        form.action = "save_recruit.php";
        return check_recruit();
    } else if (submited == "delete") {
        delete_recruit();
        return false;
    }
    return false;
}

function confirm_delete_recruit() {
    var form = document.getElementsByName("edit_recruit")[0];
    form.action = "delete_recruit.php";
    form.submit();
}

function delete_recruit() {

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
        '       <div class="weui_dialog_bd">警告!删除操作不可逆!请再次确认是否删除该则招新!</div>' +
        '       <div class="weui_dialog_ft">' +
        '           <a onclick="dialog_disappear();" class="weui_btn_dialog default">取消</a>' +
        '           <a onclick="confirm_delete_recruit();" class="weui_btn_dialog primary">确定</a>' +
        '       </div>' +
        '   </div>' +
        '</div>';

    btn.className = btn.className.replace("disabled", "");
    btn.removeAttribute("disabled");

    return false;
}

function check_profile() {

    var btn = document.getElementsByName("save")[0];
    btn.setAttribute("disabled", true);
    btn.className += " disabled";

    var error_message = "";

    var str1 = document.getElementById("password").value;
    var str2 = document.getElementById("re_password").value;
    if (str1.length > 0 || str2.length > 0) {
        if (str1 != str2) {
            error_message += "两次输入的密码不一样<br>";
        }
    }

    var str = document.getElementById("fullname").value;
    if (str.length == 0) {
        error_message += "全称不可为空<br>";
    }

    var str = document.getElementById("email").value;
    var pos = str.search(/@/);
    if (str.length == 0) {
        error_message += "联系邮箱不可为空<br>";
    } else if (str.length > 0 && !(pos > 0 && pos < str.length-1)) {
        error_message += "联系邮箱不符合规范<br>";
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
