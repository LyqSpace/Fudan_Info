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

    <title>报名/取票 | FDUTOPIA</title>
</head>
<body ontouchstart>
<div class="page_header">
    <h1 class="page_title">抢票结果</h1>
</div>

<div class="page_body">
    <?php

    function generate_registration_user_serial($length)
    {
        $chars = '0123456789';
        $serial = '';
        for ($i = 0; $i < $length; $i++) {
            $serial .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return $serial;
    }

    if (isset($_POST['registration_serial']) &&
        isset($_POST['registration_id']) &&
        isset($_POST['registration_name']) &&
        isset($_POST['registration_phone']) &&
        isset($_POST['registration_major'])
    ) {

        $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
        mysql_query("set names 'utf8'");
        mysql_select_db("fudan_info");

        $query = sprintf("SELECT * FROM event_registration_date WHERE registration_serial='%s';",
            mysql_real_escape_string($_POST['registration_serial']));
        $res = mysql_query($query, $mysql);
        $row = mysql_fetch_assoc($res);
        if ($row['register_count'] + $_POST['ticket_num'] > $row['ticket_count']) {
            ?>
            <div class="weui_mask"></div>
            <div class="weui_dialog">
                <div class="weui_dialog_hd">
                    <strong class="weui_dialog_title">票已抢完</strong>
                </div>
                <div class="weui_dialog_bd">
                    抱歉，该场次的票已全部抢完，点击确定后回到主界面。
                </div>
                <div class="weui_dialog_ft">
                    <a href="index.php" class="weui_btn_dialog primary">确定</a>
                </div>
            </div>
            <?php

        } else {

            $query = sprintf("SELECT * FROM event_registration_list WHERE registration_serial='%s' AND
                (registration_id='%s' OR registration_name='%s' OR registration_phone='%s');",
                mysql_real_escape_string($_POST['registration_serial']),
                mysql_real_escape_string($_POST['registration_id']),
                mysql_real_escape_string($_POST['registration_name']),
                mysql_real_escape_string($_POST['registration_phone']));
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
                        <p>抱歉，一个人只能注册一个场次的活动一次</p>

                        <p>同一学号、姓名、手机均算作同一个人</p>
                    </div>
                    <div class="weui_dialog_ft">
                        <a href="index.php" class="weui_btn_dialog primary">确定</a>
                    </div>
                </div>
                <?php
            } else {
                $registration_user_serial = generate_registration_user_serial(4);
                while (true) {
                    $query = sprintf("SELECT * FROM event_registration_list WHERE registration_serial='%s' AND registration_user_serial='%s';",
                        mysql_real_escape_string($_POST['registration_serial']),
                        $registration_user_serial);
                    $res = mysql_query($query, $mysql);
                    if (!mysql_num_rows($res)) break;
                    $registration_user_serial = generate_registration_user_serial(4);
                }
                $query = sprintf("INSERT INTO event_registration_list VALUE('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', TRUE, NULL);",
                    $registration_user_serial,
                    mysql_real_escape_string($_POST['registration_serial']),
                    mysql_real_escape_string($_POST['registration_id']),
                    mysql_real_escape_string($_POST['registration_name']),
                    mysql_real_escape_string($_POST['registration_major']),
                    mysql_real_escape_string($_POST['registration_phone']),
                    $_POST['ticket_num'],
                    mysql_real_escape_string($_POST['registration_message']));
                //echo $query;
                $res = mysql_query($query, $mysql);

                $query = sprintf("UPDATE event_registration_date SET register_count=register_count+%s WHERE registration_serial='%s';",
                    $_POST['ticket_num'],
                    mysql_real_escape_string($_POST['registration_serial']));
                //echo $query;
                $res = mysql_query($query, $mysql);
                ?>
                <p class="page_title">入场验证码</p>
                <p class="page_title"><?php echo $registration_user_serial; ?></p>
                <p class="page_desc">快快截图保存下来吧</p>
                <br>
                <div class="weui_btn_area">
                    <a class="weui_btn weui_btn_plain_default" href="index.php#m/page_guest_events">返回活动报名系统</a>
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
                <a href="index.php#m/page_guest_events" class="weui_btn_dialog primary">确定</a>
            </div>
        </div>
        <?php
    }
    ?>
</div>
</body>
</html>
