function dialog_disappear() {
    var dialog = document.getElementById('dialog');
    dialog.style.display = "none";
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

function check_recruit() {
    return true;
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


function add_activity() {
    var activity_list = document.getElementById("activity_list");
    var activity_cnt = activity_list.childElementCount + 1;
    var x = document.createElement("div");
    x.setAttribute("id", "activity_" + activity_cnt);
    var activity_details_name = 'activity_details_' + activity_cnt;
    var activity_details_cnt = 'activity_details_cnt_' + activity_cnt;
    x.innerHTML =
        '<div class="weui_cells weui_cells_form">' +
        '   <div class="weui_cell">' +
        '       <div class="weui_cell_hd">' +
        '           <label class="weui_label">活动' + activity_cnt + '</label>' +
        '       </div>' +
        '       <div class="weui_cell_bd weui_cell_primary">' +
        '           <input class="weui_input" name="activity_name_' + activity_cnt + '" maxlength="30" type="text" required="required""/>' +
        '       </div>' +
        '   </div>' +
        '   <div class="weui_cell">' +
        '       <div class="weui_cell_hd">' +
        '           <label class="weui_label">时间</label>' +
        '       </div>' +
        '       <div class="weui_cell_bd weui_cell_primary">' +
        '           <input class="weui_input" name="activity_date_' + activity_cnt + '" maxlength="30" type="text" required="required""/>' +
        '       </div>' +
        '   </div>' +
        '   <div class="weui_cell">' +
        '       <div class="weui_cell_hd">' +
        '           <label class="weui_label">地点</label>' +
        '       </div>' +
        '       <div class="weui_cell_bd weui_cell_primary">' +
        '           <input class="weui_input" name="activity_location_' + activity_cnt + '" maxlength="30" type="text" required="required""/>' +
        '       </div>' +
        '   </div>' +
        '   <div class="weui_cell">' +
        '       <div class="weui_cell_bd weui_cell_primary">' +
        '           <textarea class="weui_textarea" id=\"' + activity_details_name + '\" placeholder="请输入该常规/大型活动的介绍。此栏不可为空。"' +
        '               name=\"' + activity_details_name + '\" rows="3" required="required"' +
        '               onkeyup="count(\'' + activity_details_name + '\', ' + activity_details_cnt + ', 200);"></textarea>' +
        '   <div class="weui_textarea_counter">' +
        '       <span id=\"' + activity_details_cnt + '\">0</span>/200' +
        '   </div>' +
        '</div>';
    activity_list.appendChild(x);
}

function del_activity() {
    var activity_list = document.getElementById("activity_list");
    var activity_cnt = activity_list.childElementCount;
    if (activity_cnt == 1) return;
    var activity = document.getElementById("activity_" + activity_cnt);
    activity.parentNode.removeChild(activity);
}