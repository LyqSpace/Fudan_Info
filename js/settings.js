function logout() {
    var d = new Date();
    d.setTime(d.getTime()-1);
    document.cookie = "login_serial=; expires=" + d.toUTCString();
    window.location.href = "index.php";
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