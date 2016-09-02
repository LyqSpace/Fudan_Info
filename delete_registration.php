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

    <script type="text/javascript" src="js/registration.js"></script>

    <title>删除一则招新 | FDUTOPIA</title>
</head>

<body ontouchstart>
<?php

if (isset($_POST['event_id']) && $_POST['event_id'] != '') {

    $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
    mysql_query("set names 'utf8'");
    mysql_select_db("fudan_info");

    $query = sprintf("SELECT * FROM event_info WHERE event_id='%s';",
        mysql_real_escape_string($_POST['event_id']));
    $res = mysql_query($query, $mysql);
    $row = mysql_fetch_assoc($res);

    if ($row['username'] == $username) {

        $query = sprintf("DELETE FROM event_registration_common WHERE event_id='%s';",
            mysql_real_escape_string($_POST['event_id']));
        mysql_query($query, $mysql);

        $query = sprintf("SELECT * FROM event_registration_date WHERE event_id='%s';",
            mysql_real_escape_string($_POST['event_id']));
        $res = mysql_query($query, $mysql);
        while ($row = mysql_fetch_assoc($res)) {
            $query = sprintf("DELETE FROM event_registration_list WHERE registration_serial='%s';",
                mysql_real_escape_string($row['registration_serial']));
            mysql_query($query, $mysql);
        }
        $query = sprintf("DELETE FROM event_registration_date WHERE event_id='%s';",
            mysql_real_escape_string($_POST['event_id']));
        mysql_query($query, $mysql);

        ?>
        <div class="weui_mask"></div>
        <div class="weui_dialog">
            <div class="weui_dialog_hd">
                <strong class="weui_dialog_title">删除结果</strong>
            </div>
            <div class="weui_dialog_bd">
                <p>报名表删除成功! 点击“确定”跳转到活动管理</p>
            </div>
            <div class="weui_dialog_ft"><a href="manager.php#m/page_events" class="weui_btn_dialog primary">确定</a>
            </div>
        </div>
        <?php

    } else {
        ?>
        <div class="weui_mask"></div>
        <div class="weui_dialog">
            <div class="weui_dialog_hd">
                <strong class="weui_dialog_title">访问违规</strong>
            </div>
            <div class="weui_dialog_bd">
                只能删除自己的报名表!
            </div>
            <div class="weui_dialog_ft">
                <a href="manager.php#m/page_events" weui_btn_dialog primary">确定</a>
            </div>
        </div>
        <?php
    }

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
            <a href="manager.php#m/page_events" class="weui_btn_dialog primary">确定</a>
        </div>
    </div>
    <?php
}

?>
</body>
</html>

