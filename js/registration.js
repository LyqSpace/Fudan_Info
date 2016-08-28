function dialog_disappear() {
    var dialog = document.getElementById('dialog');
    dialog.style.display = "none";
}

window.onload =function () {
    show_register_date();
    show_register_type();
};

function reedit_registration(submited) {

    var form = document.getElementsByName("edit_registration")[0];
    if (submited == "save") {
        check_registration();
    } else if (submited == "delete") {
        delete_registration();
        return false;
    }
    return false;
}

function confirm_delete_registration() {
    var form = document.getElementsByName("edit_registration")[0];
    form.action = "delete_registration.php";
    form.submit();
}

function confirm_save_registration() {
    var form = document.getElementsByName("edit_registration")[0];
    form.action = "save_registration.php";
    form.submit();
}

function delete_registration() {

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
        '       <div class="weui_dialog_bd"><span id="info_red">删除操作不可逆</span>!<br>同时被删去的还有用户的报名记录。<br>请再次确认是否删除该报名表。</div>' +
        '       <div class="weui_dialog_ft">' +
        '           <a onclick="dialog_disappear();" class="weui_btn_dialog primary">取消</a>' +
        '           <a onclick="confirm_delete_registration();" class="weui_btn_dialog default">确定</a>' +
        '       </div>' +
        '   </div>' +
        '</div>';

    btn.className = btn.className.replace("disabled", "");
    btn.removeAttribute("disabled");

    return false;
}

function check_registration() {

    var btn = document.getElementsByName("save")[0];
    btn.setAttribute("disabled", true);
    btn.className += " disabled";

    var confirm = document.getElementsByName("confirm")[0];
    if (confirm.checked == "") {

        btn.className = btn.className.replace("disabled", "");
        btn.removeAttribute("disabled");

        confirm_save_registration();

    } else {
        var confirm_box = document.getElementById("confirm_message");
        confirm_box.innerHTML =
            '<div id="dialog">' +
            '   <div class="weui_mask"></div>' +
            '   <div class="weui_dialog">' +
            '       <div class="weui_dialog_hd">' +
            '           <strong class="weui_dialog_title">发布确认</strong>' +
            '       </div>' +
            '       <div class="weui_dialog_bd"><span id="info_red">发布操作不可逆</span>!<br>所有信息将永久保存并对外发布。<br>请再次确认是否发布该报名表。</div>' +
            '       <div class="weui_dialog_ft">' +
            '           <a onclick="dialog_disappear();" class="weui_btn_dialog primary">取消</a>' +
            '           <a onclick="confirm_save_registration();" class="weui_btn_dialog default">确定</a>' +
            '       </div>' +
            '   </div>' +
            '</div>';
    }

    btn.className = btn.className.replace("disabled", "");
    btn.removeAttribute("disabled");

}

function add_date() {
    var date_list = document.getElementById("date_list");
    var date_cnt = date_list.childElementCount + 1;
    var x = document.createElement("div");
    x.setAttribute("id", "date_" + date_cnt);
    x.innerHTML =
        '<div class="weui_cells weui_cells_form">' +
        '   <div class="weui_cell">' +
        '       <div class="weui_cell_hd">' +
        '           <label class="weui_label">场次' + date_cnt + '</label>' +
        '       </div>' +
        '       <div class="weui_cell_bd weui_cell_primary">' +
        '           <input class="weui_input" name="date_' + date_cnt + '" type="datetime-local" required="required"/>' +
        '       </div>' +
        '   </div>' +
        '   <div class="weui_cell">' +
        '       <div class="weui_cell_hd"><label class="weui_label">总票数</label></div>' +
        '       <div class="weui_cell_bd weui_cell_primary">' +
        '           <input class="weui_input" type="number" required="required" name="ticket_count_' + date_cnt + '" pattern="[0-9]*" placeholder="请输入可以通过此方式取票/报名的总票数">' +
        '       </div>' +
        '   </div>' +
        '</div>';
    date_list.appendChild(x);
}

function del_date() {
    var date_list = document.getElementById("date_list");
    var date_cnt = date_list.childElementCount;
    if (date_cnt == 1) return;
    var date = document.getElementById("date_" + date_cnt);
    date.parentNode.removeChild(date);
}