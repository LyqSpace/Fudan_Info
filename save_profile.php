<?php

$username = '';

if (isset($_COOKIE['login_serial'])) {
    $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
    mysql_select_db("fudan_info");
    $query = sprintf("SELECT username FROM login_serial WHERE serial='%s';",
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
    <meta name="keywords" content="FDUTOPIA, FUDAN, INFORMATION, 复旦">
    <meta name="author" content="Liang Yongqing, Liu Xueyue">

    <link rel="stylesheet" type="text/css" href="css/weui.min.css"/>
    <link rel="stylesheet" type="text/css" href="css/style.css"/>

    <title>保存个人信息 | FDUTOPIA</title>
</head>

<body ontouchstart>
<?php

if (isset($_POST['fullname']) && $_POST['fullname'] != "" &&
    isset($_POST['email']) && $_POST['email'] != ""
) {

    $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
    mysql_query("set names 'utf8'");
    mysql_select_db("fudan_info");

    if (isset($_POST['password']) && $_POST['password'] != '') {
        $query = sprintf("UPDATE users SET fullname='%s', email='%s', password='%s', user_category='%s' WHERE username='%s';",
            mysql_real_escape_string($_POST['fullname']),
            mysql_real_escape_string($_POST['email']),
            md5($_POST['password']),
            mysql_real_escape_string($_POST['user_category']),
            $username);
    } else {
        $query = sprintf("UPDATE users SET fullname='%s', email='%s', user_category='%s' WHERE username='%s';",
            mysql_real_escape_string($_POST['fullname']),
            mysql_real_escape_string($_POST['email']),
            mysql_real_escape_string($_POST['user_category']),
            $username);
    }

    $res = mysql_query($query, $mysql);

    ?>
    <div class="weui_mask"></div>
    <div class="weui_dialog">
        <div class="weui_dialog_hd">
            <strong class="weui_dialog_title">保存结果</strong>
        </div>
        <div class="weui_dialog_bd">
            <?php
            if ($res) {
                echo "信息保存成功! 点击“确定”跳转到我的主页";
            } else {
                echo "信息保存失败! 点击“确定”返回编辑界面<br>错误代码<br>" . mysql_error() . "<br>请发送错误代码联系管理员fdutopia@lyq.me";
            }
            ?>
        </div>
        <div class="weui_dialog_ft">
            <a href="
            <?php
            if ($res) {
                echo "manager.php#page_settings";
            } else {
                echo "javascript:history.back();";
            }
            ?>" class="weui_btn_dialog primary">确定</a>
        </div>
    </div>
    <?php

    mysql_close($mysql);

} else {
    ?>
    <div class="weui_mask"></div>
    <div class="weui_dialog">
        <div class="weui_dialog_hd">
            <strong class="weui_dialog_title">违规访问</strong>
        </div>
        <div class="weui_dialog_bd">
            本页面禁止违规访问!
        </div>
        <div class="weui_dialog_ft">
            <a href="manager.php#page_settings" class="weui_btn_dialog primary">确定</a>
        </div>
    </div>
    <?php
}

?>
</body>
</html>
