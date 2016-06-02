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
    <title>报名/取票 | FDUTOPIA</title>
</head>
<body ontouchstart>
<div class="page_header">
<!--    <div class="logo_img"></div>-->
</div>

<div class="page_body">
<?php

function generate_register_serial($length) {
    $chars = '0123456789';
    $serial = '';
    for ($i = 0; $i < $length; $i++) {
        $serial .= $chars[mt_rand(0, strlen($chars) - 1)];
    }
    return $serial;
}

    if (isset($_POST['registration_serial'])) {

        $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
        mysql_query("set names 'utf8'");
        mysql_select_db("fudan_info");

        $query = sprintf("select * from event_date where registration_serial='%s';",
            mysql_real_escape_string($_POST['registration_serial']));
        $res = mysql_query($query, $mysql);
        $row = mysql_fetch_assoc($res);
        $ticket_num = $_POST['ticket_num_' . $_POST['registration_serial']];
        if ($row['register_count'] + $ticket_num >= $row['ticket_count']) {
            ?>
            <div class="weui_mask"></div>
            <div class="weui_dialog">
                <div class="weui_dialog_hd">
                    <strong class="weui_dialog_title">票已抢完</strong>
                </div>
                <div class="weui_dialog_bd">
                    抱歉，该场次的票已全部抢完，点击确定后返回。
                </div>
                <div class="weui_dialog_ft">
                    <a href="javascript:history.back();" class="weui_btn_dialog primary">确定</a>
                </div>
            </div>
            <?php

        } else {
            $register_serial = generate_register_serial(6);
            while (true) {
                $query = sprintf("select * from event_register_list where register_serial=%s;", $register_serial);
                $res = mysql_query($query, $mysql);
                if (!mysql_num_rows($res)) break;
                $register_serial = generate_register_serial(6);
            }
            $query = sprintf("insert into event_register_list value('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', null);",
                $register_serial,
                mysql_real_escape_string($_POST['registration_serial']),
                mysql_real_escape_string($_POST['registration_id']),
                mysql_real_escape_string($_POST['registration_name']),
                mysql_real_escape_string($_POST['registration_major']),
                mysql_real_escape_string($_POST['registration_phone']),
                mysql_real_escape_string($_POST['ticket_num_' . $_POST['registration_serial']]),
                mysql_real_escape_string($_POST['message_' . $_POST['registration_serial']]));
            //echo $query;
            $res = mysql_query($query, $mysql);

            $query = sprintf("update event_date set register_count=register_count+%s where registration_serial='%s';",
                $ticket_num,
                mysql_real_escape_string($_POST['registration_serial']));
            //echo $query;
            $res = mysql_query($query, $mysql);
            ?>
            <p class="page_title">抢票成功</p>
            <p class="page_title">入场验证码</p>
            <p class="page_title"><?php echo $register_serial;?></p>
            <p class="page_desc">快快截图保存下来吧</p>
            <br>
            <hr>
            <p class="page_desc">素心无用，自由分享</p>
            <p class="page_desc">分享复旦内有生命力的讲座活动</p>
            <p class="page_desc">守望复旦人澎湃不息的赤子之心</p>
            <br>
            <p class="page_desc">如果你喜欢我们，欢迎关注公众号FDUTOPIA</p>
            <div class="qrcode_img"></div>
            <br>
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
            <a href="javascript:history.back();" class="weui_btn_dialog primary">确定</a>
        </div>
    </div>
<?php
    }
?>
    </div>
</body>
</html>
