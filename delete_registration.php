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

    <script type="text/javascript" src="js/registration.js"></script>

    <title>删除一则招新 | FDUTOPIA</title>
</head>

<body ontouchstart>
<?php

if (isset($_POST['event_id']) && $_POST['event_id'] != '') {

    $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
    mysql_query("set names 'utf8'");
    mysql_select_db("fudan_info");

    $query = sprintf("select * from event_info where event_id='%s';",
        mysql_real_escape_string($_POST['event_id']));
    $res = mysql_query($query, $mysql);
    $row = mysql_fetch_assoc($res);

    if ($row['username'] == $username) {

        $query = sprintf("delete from event_registration_common where event_id='%s';",
            mysql_real_escape_string($_POST['event_id']));
        mysql_query($query, $mysql);

        $query = sprintf("select * from event_registration_date where event_id='%s';",
            mysql_real_escape_string($_POST['event_id']));
        $res = mysql_query($query, $mysql);
        while ($row = mysql_fetch_assoc($res)) {
            $query = sprintf("delete from event_registration_list where registration_serial='%s';",
                mysql_real_escape_string($row['registration_serial']));
            mysql_query($query, $mysql);
        }
        $query = sprintf("delete from event_registration_date where event_id='%s';",
            mysql_real_escape_string($_POST['event_id']));
        mysql_query($query, $mysql);

        ?>
        <div class="weui_mask"></div>
        <div class="weui_dialog">
            <div class="weui_dialog_hd">
                <strong class="weui_dialog_title">删除结果</strong>
            </div>
            <div class="weui_dialog_bd">
                <?php
                if ($res) {
                    echo "报名表删除成功! 点击“确定”跳转到活动管理";
                } else {
                    echo "报名表删除失败! 点击“确定”返回编辑界面<br>错误代码<br>" . mysql_error() . "<br>请发送错误代码联系管理员fdutopia@lyq.me";
                }
                ?>
            </div>
            <div class="weui_dialog_ft">
                <a href="
            <?php
                if ($res) {
                    echo "manager.php#m/page_events";
                } else {
                    echo "javascript:history.back();";
                }
                ?>" class="weui_btn_dialog primary">确定</a>
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
                <a href="javascript:history.back();" weui_btn_dialog primary">确定</a>
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
            <a href="javascript:history.back()" class="weui_btn_dialog primary">确定</a>
        </div>
    </div>
    <?php
}

?>
</body>
</html>

