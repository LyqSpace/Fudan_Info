var i = document.createElement("input");
i.style.display = "none";
i.setAttribute("type", "datetime-local");
if (i.type === "text") {
    var msg_box = document.getElementById("error_message");
    msg_box.innerHTML =
        '<div id="dialog">' +
        '   <div class="weui_mask"></div>' +
        '   <div class="weui_dialog">' +
        '       <div class="weui_dialog_hd">' +
        '           <strong class="weui_dialog_title">抱歉，我们的网站需要HTML5支持</strong>' +
        '      </div>' +
        '       <div class="weui_dialog_bd">' +
        '           不好意思，您的浏览器版本比较低<br>请使用其它主流的浏览器～' +
        '       </div>' +
        '       <div class="weui_dialog_ft">' +
        '           <a href="javascript:history.back();" class="weui_btn_dialog primary">确定</a>' +
        '       </div>' +
        '   </div>' +
        '</div>';
}