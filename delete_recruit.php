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

    <script type="text/javascript" src="js/recruit.js"></script>

    <title>删除一则招新 | FDUTOPIA</title>
</head>

<body ontouchstart>
<?php

$mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
mysql_query("set names 'utf8'");
mysql_select_db("fudan_info");

$query = sprintf("DELETE FROM recruit_info_common WHERE username='%s';", $username);
$res = mysql_query($query, $mysql);
$query = sprintf("DELETE FROM recruit_info_activities WHERE username='%s';", $username);
$res = mysql_query($query, $mysql);

mysql_close($mysql);
?>
<div class="weui_mask"></div>
<div class="weui_dialog">
    <div class="weui_dialog_hd">
        <strong class="weui_dialog_title">删除结果</strong>
    </div>
    <div class="weui_dialog_bd">
        <p>招新删除成功! 点击“确定”跳转到我的招新管理</p>
    </div>
    <div class="weui_dialog_ft"><a href="manager.php#page_recruits" class="weui_btn_dialog primary">确定</a>
    </div>
</div>
</body>
</html>

