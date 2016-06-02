function check_registration() {

    var btn = document.getElementsByName("save")[0];
    btn.setAttribute("disabled", true);
    btn.className += " disabled";

    var error_message = "";

    var registration_id = document.getElementById("registration_id");
    var registration_name = document.getElementById("registration_name");
    var registration_major = document.getElementById("registration_major");
    var registration_phone = document.getElementById("registration_phone");

    if (registration_id.checked== "" && registration_name.checked=="" && registration_major=="" && registration_phone=="") {
        error_message += "至少选择一个用户信息";
    }

    var ticket_count = document.getElementsByName("ticket_count")[0];
    if (ticket_count <= 0) {
        error_message += "总票数应大于零";
    }
    var ticket_per_person = document.getElementsByName("ticket_per_person")[0];
    if (ticket_count <= 0) {
        error_message += "每位用户最多能取的票数应大于零";
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

function add_date() {
    var date_list = document.getElementById("date_list");
    var date_cnt = date_list.childElementCount + 1;
    var x = document.createElement("div");
    x.setAttribute("class", "weui_cells weui_cells_form");
    x.setAttribute("id", "date_" + date_cnt);
    x.innerHTML =
        '<div class="weui_cell">' +
        '<div class="weui_cell_hd">' +
        '   <label class="weui_label">场次' + date_cnt + '</label>' +
        '</div>' +
        '<div class="weui_cell_bd weui_cell_primary">' +
        '   <input class="weui_input" name="date_' + date_cnt + '" type="datetime-local" required="required"/>' +
        '</div>' +
        '</div>' +
        '<div class="weui_cell">' +
        '   <div class="weui_cell_hd"><label class="weui_label">总票数</label></div>' +
        '   <div class="weui_cell_bd weui_cell_primary">' +
        '       <input class="weui_input" type="number" required="required" name="ticket_count_' + date_cnt + '" pattern="[0-9]*" placeholder="请输入可以通过此方式取票/报名的总票数">' +
        '</div>' +
        '</div></div>';
    date_list.appendChild(x);
}

function del_date() {
    var date_list = document.getElementById("date_list");
    var date_cnt = date_list.childElementCount;
    if (date_cnt == 1) return;
    var date = document.getElementById("date_" + date_cnt);
    date.parentNode.removeChild(date);
}