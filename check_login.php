<?php

function generate_serial($length) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $serial = '';
    for ($i = 0; $i < $length; $i++) {
        $serial .= $chars[mt_rand(0, strlen($chars) - 1)];
    }
    return $serial;
}

if (isset($_POST['username']) && isset($_POST['password'])) {
    $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
    mysql_select_db("fudan_info");
    $query = sprintf("select password from users where username='%s';",
        mysql_real_escape_string($_POST['username']));
    $res = mysql_query($query, $mysql);
    $row = mysql_fetch_assoc($res);
    if ($row['password'] == md5($_POST['password'])) {
        $query = sprintf("delete from login_serial where username='%s';",
            mysql_real_escape_string($_POST['username']));
        $res = mysql_query($query);
        $serial = generate_serial(32);
        $query = sprintf("insert into login_serial value('%s', '%s');",
            mysql_real_escape_string($_POST['username']),
            mysql_real_escape_string($serial));
        $res = mysql_query($query);
        mysql_close($mysql);
        setcookie('login_serial', $serial, time()+60*60*24*20);
        if ($_POST['username'] == 'admin') {
            header('Location: admin.php');
        } else {
            header('Location: index.php');
        }
    } else {
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
            <title>FDUTOPIA</title>
        </head>

        <body ontouchstart>
            <div class="weui_mask"></div>
            <div class="weui_dialog">
                <div class="weui_dialog_hd">
                    <strong class="weui_dialog_title">登录失败</strong>
                </div>
                <div class="weui_dialog_bd">
                    用户名或密码错误！
                </div>
                <div class="weui_dialog_ft">
                    <a href="login.html" class="weui_btn_dialog primary">确定</a>
                </div>
            </div>
        </body>
        </html>
        <?php
    }
}
?>