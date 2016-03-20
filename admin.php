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
        if ($row['username'] != 'admin') {
            header('Location: login.html');
        }
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
    <title>控制面板</title>
</head>

<body ontouchstart>
    <div class="page_header">
        <h1 class="page_title">控制面板</h1>
        <p class="page_desc">现有用户数 : <?php
            $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
            mysql_query("set names 'utf8'");
            mysql_select_db("fudan_info");
            $query = "select count(*) as cnt from users;";
            $res = mysql_query($query, $mysql);
            $row = mysql_fetch_assoc($res);
            echo $row['cnt']-2;
            mysql_close($mysql);
            ?></p>
    </div>
    <div class="page_body">
        <div class="weui_btn_area">
            <a class="weui_btn weui_btn_plain_primary" href="batch_event.php"">批处理活动</a>
        </div>
        <div class="weui_btn_area">
            <a class="weui_btn weui_btn_plain_primary" href="batch_recruit.php">批处理招新</a>
        </div>
        <div class="weui_btn_area">
            <a class="weui_btn weui_btn_plain_primary" href="admin_user.php">用户管理</a>
        </div>
        <div class="weui_btn_area">
            <a class="weui_btn weui_btn_plain_default" onclick="logout()">退出</a>
        </div>
    </div>
</body>
</html>
