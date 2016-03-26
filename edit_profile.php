<?php

$username = '';

if (isset($_COOKIE['login_serial'])) {
    $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
    mysql_select_db("fudan_info");
    $query = sprintf("select username from login_serial where serial='%s';",
        mysql_real_escape_string($_COOKIE['login_serial']));
    $res = mysql_query($query, $mysql);
    mysql_close($mysql);
    if (!mysql_num_rows($res)) {
        header('Location: login.html');
    } else {
        $row = mysql_fetch_assoc($res);
        $username = $row['username'];
    }
} else {
    header('Location: login.html');
}
?>

<html lang="en">
<!-- Welcome! Contact Me: root@lyq.me -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <meta name="keywords" content="Fudan, Informations">
    <meta name="author" content="Liang Yongqing, Liu Xueyue">
    <link rel="stylesheet" type="text/css" href="weui.min.css" />
    <link rel="stylesheet" type="text/css" href="style.css" />
    <script type="text/javascript" src="functions.js"></script>
    <title>编辑个人信息 | FDUTOPIA</title>
</head>

<?php
    $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
    mysql_query("set names 'utf8'");
    mysql_select_db("fudan_info");
    $query = sprintf("select * from users where username='%s';", $username);
    $res = mysql_query($query, $mysql);
    $row = mysql_fetch_assoc($res);
?>

<body ontouchstart>
    <div class="page_header">
        <h1 class="page_title">编辑个人信息</h1>
        <p class="page_desc">社团/主办方的全称将显示在推送中</p>
        <p class="page_desc">联系邮箱不可为空</p>
    </div>
    <div class="page_body">
        <form name="update_users" method="post" onsubmit="return check_profile(this);" action="save_profile.php">
            <div class="weui_cells weui_cells_form">
                <div class="weui_cell">
                    <div class="weui_cell_hd">
                        <label class="weui_label">密码</label>
                    </div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input" name="password" id="password" type="password" placeholder="请输入密码" />
                    </div>
                </div>
                <div class="weui_cell">
                    <div class="weui_cell_hd">
                        <label class="weui_label">重复</label>
                    </div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input" name="re_password" id="re_password" type="password" placeholder="请再次输入密码" />
                    </div>
                </div>
                <div class="weui_cell">
                    <div class="weui_cell_hd">
                        <label class="weui_label">全称</label>
                    </div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input" name="fullname" id="fullname" type="text" placeholder="全称将显示在推送中" value="<?php echo $row['fullname'];?>"/>
                    </div>
                </div>
                <div class="weui_cell">
                    <div class="weui_cell_hd">
                        <label class="weui_label">邮箱</label>
                    </div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input" name="email" id="email" type="text" placeholder="联系邮箱用于找回密码" value="<?php if ($row['email'] != 'null') echo $row['email'];?>"/>
                    </div>
                </div>
            </div>
            <div class="weui_btn_area">
                <input class="weui_btn weui_btn_plain_primary" name="save" type="submit" value="保存" />
            </div>
        </form>
        <div class="weui_btn_area">
            <a class="weui_btn weui_btn_plain_default" href="index.php">返回</a>
        </div>
        <div id="error_message"></div>
    </div>
<br>
</body>
</html>

<?php
    mysql_close($mysql);
?>