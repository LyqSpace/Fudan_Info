function dialog_disappear() {
    var dialog = document.getElementById('dialog');
    dialog.style.display = "none";
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