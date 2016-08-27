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
    <meta name="keywords" content="FDUTOPIA, FUDAN, INFORMATION, 复旦">
    <meta name="author" content="Liang Yongqing, Liu Xueyue">

    <link rel="stylesheet" type="text/css" href="css/weui.min.css"/>
    <link rel="stylesheet" type="text/css" href="css/style.css"/>

    <script type="text/javascript" src="js/recruit.js"></script>

    <title>保存一则招新 | FDUTOPIA</title>
</head>

<body ontouchstart>
<?php

if (isset($_POST['details']) && $_POST['details'] != "") {

    $error_msg = "";

    $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
    mysql_query("set names 'utf8'");
    mysql_select_db("fudan_info");

    $query = sprintf("delete from recruit_info_common where username='%s';", $username);
    $res = mysql_query($query, $mysql);

    $query = sprintf("insert into recruit_info_common value ('%s', '%s', null);",
            $username,
            mysql_real_escape_string($_POST['details']));
    $res = mysql_query($query, $mysql);
    if (!$res) $error_msg .= "保存“招新概况”失败!<br>";

    $query = sprintf("delete from recruit_info_activities where username='%s';", $username);
    $res = mysql_query($query, $mysql);

    $activity_cnt = 1;

    while (true) {

        $activity_name = "activity_name_" . $activity_cnt;
        $activity_date = "activity_date_" . $activity_cnt;
        $activity_location = "activity_location_" .$activity_cnt;
        $activity_details = "activity_details_" . $activity_cnt;

        if (!isset($_POST[$activity_name])) break;

        $query = sprintf("insert into recruit_info_activities value (null, '%s', '%s', '%s', '%s', '%s');",
            $username,
            mysql_real_escape_string($_POST[$activity_name]),
            mysql_real_escape_string($_POST[$activity_date]),
            mysql_real_escape_string($_POST[$activity_location]),
            mysql_real_escape_string($_POST[$activity_details]));
        //echo $query;
        mysql_query($query, $mysql);

        $activity_cnt++;
    }
    ?>
    <div class="weui_mask"></div>
    <div class="weui_dialog">
        <div class="weui_dialog_hd">
            <strong class="weui_dialog_title">保存结果</strong>
        </div>
        <div class="weui_dialog_bd">
            <p>招新保存成功! 点击“确定”跳转到我的招新管理</p>
        </div>
        <div class="weui_dialog_ft">
            <a href="manager.php#m/page_recruits" class="weui_btn_dialog primary">确定</a>
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
            <a href="manager.php" class="weui_btn_dialog primary">确定</a>
        </div>
    </div>
    <?php
}

?>
</body>
</html>
