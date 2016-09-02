<?php
$recruit_register_timestamp = "2016-08-01 00:00:00";
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

    <script type="text/javascript" src="js/register_events.js"></script>

    <title>报名招新 | FDUTOPIA</title>
</head>
<body ontouchstart>
<div class="page_header">
    <h1 class="page_title">报名结果</h1>
</div>

<div class="page_body">
    <?php

    if (isset($_POST['username']) &&
        isset($_POST['register_recruit_id']) &&
        isset($_POST['register_recruit_name']) &&
        isset($_POST['register_recruit_phone']) &&
        isset($_POST['register_recruit_major'])
    ) {

        $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
        mysql_query("set names 'utf8'");
        mysql_select_db("fudan_info");

        $query = sprintf("SELECT * FROM recruit_list WHERE username='%s' AND recruit_register_time>'%s' AND
                (guest_id='%s' OR guest_name='%s' OR guest_phone='%s');",
            mysql_real_escape_string($_POST['username']),
            $recruit_register_timestamp,
            mysql_real_escape_string($_POST['register_recruit_id']),
            mysql_real_escape_string($_POST['register_recruit_name']),
            mysql_real_escape_string($_POST['register_recruit_phone']));
        //echo $query;
        $res = mysql_query($query, $mysql);
        $row = mysql_fetch_assoc($res);
        if (mysql_num_rows($res)) {
            ?>
            <div class="weui_mask"></div>
            <div class="weui_dialog">
                <div class="weui_dialog_hd">
                    <strong class="weui_dialog_title">不可重复报名</strong>
                </div>
                <div class="weui_dialog_bd">
                    <p>您在本学期已经报名了该社团</p>

                    <p>同一学号、姓名、手机均算作同一个人</p>
                </div>
                <div class="weui_dialog_ft">
                    <a href="index.php#m/page_guest_recruits" class="weui_btn_dialog primary">确定</a>
                </div>
            </div>
            <?php
        } else {

            $query = sprintf("SELECT * FROM recruit_info_activities WHERE username='%s';", mysql_real_escape_string($_POST['username']));
            //echo $query;
            $res = mysql_query($query, $mysql);

            $activity_cnt = 0;
            $activity_item = 'activity_' . $activity_cnt;
            $recruit_items = '';
            while (isset($_POST[$activity_item])) {
                $row = mysql_fetch_assoc($res);
                if ($_POST[$activity_item] == "on") {
                    $recruit_items .= $row['activity_name'] . ' ';
                }
                $activity_cnt++;
                $activity_item = 'activity_' . $activity_cnt;
            }
            if ($recruit_items == '') $recruit_items = '无勾选任何活动';

            $join_management = 'false';
            if ($_POST['join_management'] == "on") {
                $join_management = 'true';
            }
            $query = sprintf("INSERT INTO recruit_list VALUE(NULL, '%s', '%s', '%s', '%s', '%s', '%s', '%s', %s, TRUE, NULL);",
                mysql_real_escape_string($_POST['username']),
                mysql_real_escape_string($_POST['register_recruit_id']),
                mysql_real_escape_string($_POST['register_recruit_name']),
                mysql_real_escape_string($_POST['register_recruit_phone']),
                mysql_real_escape_string($_POST['register_recruit_major']),
                mysql_real_escape_string($_POST['register_recruit_message']),
                $recruit_items,
                $join_management);
            //echo $query;
            $res = mysql_query($query, $mysql);
            ?>
            <p class="page_title">成功</p>
            <br>
            <div class="weui_btn_area">
                <a class="weui_btn weui_btn_plain_default" href="index.php#m/page_guest_recruits">返回招新报名系统</a>
            </div>
            <br>
            <hr>
            <br>
            <p class="page_desc">素心无用，自由分享</p>
            <p class="page_desc">如果你喜欢我们，欢迎关注公众号FDUTOPIA</p>

            <div class="median_img">
                <img src="images/qrcode_median.jpg" alt="qrcode"/>
            </div>
            <?php
        }
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
                <a href="index.php#m/page_guest_recruits" class="weui_btn_dialog primary">确定</a>
            </div>
        </div>
        <?php
    }
    ?>
</div>
</body>
</html>
