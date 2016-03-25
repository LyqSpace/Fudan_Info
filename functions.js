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

function show_register() {
    register_form = document.getElementById('register_form');
    register_check = document.getElementsByName('register')[0];
    if (register_check == null) return;
    if (register_check.checked) {
        register_form.style.display = "block";
    } else {
        register_form.style.display = "none";
    }
}

window.onload = function () {
    show_details();
    show_register();
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

function check_event() {

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

    var check_ele = document.getElementsByName("date_st");
    if (check_ele[0].value == "") error_message += "活动开始时间不可为空<br>";

    var check_ele = document.getElementsByName("date_ed");
    if (check_ele[0].value == "") error_message += "活动结束时间不可为空<br>";

    var date_st = document.getElementsByName("date_st")[0].value;
    var date_ed = document.getElementsByName("date_ed")[0].value;
    if (date_st != "" && date_ed != "") {
        if (date_st >= date_ed) error_message += "活动结束时间不可早于开始时间<br>";
    }

    var check_ele = document.getElementsByName("category");
    if (check_ele[0].value == "") error_message += "类别不可为空<br>";

    var register_st = document.getElementsByName("register_st")[0].value;
    var register_ed = document.getElementsByName("register_ed")[0].value;
    if (register_st != "" && register_ed != "") {
        if (register_st >= register_ed) error_message += "报名结束时间不可早于开始时间<br>";
    }
    var register_ele = document.getElementsByName("register")[0];
    if (register_ele.checked == true && (register_st == "" || register_ed == "")) error_message += "报名时间不可为空<br>";
    if (register_ele.checked == false && (register_st != "" || register_ed != "")) error_message += "报名时间不为空，请勾选需要提前报名<br>";

    var str = document.getElementById("details_text").value;
    var len = 0;
    for (var i = 0; i < str.length; i++) {
        if (str.charCodeAt(i) > 255) {
            len += 2;
        } else {
            len += 1;
        }
    }
    if (len > 300) error_message += "详细描述超过字数限制<br>";
    var check_ele = document.getElementsByName("notification");
    if (check_ele[0].checked == true && len == 0) error_message += "详细描述不可为空<br>";
    if (check_ele[0].checked == false && len > 0) error_message += "详细描述不为空，请勾选有详细描述<br>";

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
        return false;
    }
}

function reedit_event(submited) {

    var form = document.getElementsByName("edit_event")[0];
    if (submited == "save") {
        form.action = "save_event.php";
        return check_event();
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
    return false;
}

function check_recruit() {

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
    return false;
}

function check_profile() {

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
        return false;
    }
}
