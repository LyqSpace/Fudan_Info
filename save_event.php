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

    <script type="text/javascript" src="js/event.js"></script>

    <title>保存一则活动 | FDUTOPIA</title>
</head>

<body ontouchstart>
<?php

function mysql_date_format($input_date) {

    $pos = strpos($input_date, "T");
    $date = substr($input_date, 0, $pos);
    $date .= " ";
    $date .= substr($input_date, $pos+1, strlen($input_date)-$pos-1);
    return $date;
}

function generate_short_url($length) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $serial = '';
    for ($i = 0; $i < $length; $i++) {
        $serial .= $chars[mt_rand(0, strlen($chars) - 1)];
    }
    return $serial;
}

if (isset($_POST['title']) && $_POST['title'] != "" &&
    isset($_POST['location']) && $_POST['location'] != "" &&
    isset($_POST['date']) && $_POST['date'] != "" &&
    isset($_POST['category']) && $_POST['category'] != "") {

    $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
    mysql_query("set names 'utf8'");
    mysql_select_db("fudan_info");

    $speaker = 'null';
    if (isset($_POST['speaker']) && $_POST['speaker'] != "") {
        $speaker = "'" . mysql_real_escape_string($_POST['speaker']) . "'";
    }

    $date = mysql_date_format($_POST['date']);
    $register_date = mysql_date_format($_POST['register_date']);

    $notification = 'false';
    if (isset($_POST['notification']) && $_POST['notification'] == "on") {
        $notification = 'true';
    }
    $register = 'false';
    if (isset($_POST['register']) && $_POST['register'] == "on") {
        $register = 'true';
    }
    $details = 'null';
    if (isset($_POST['details']) && $_POST['details'] != "") {
        $details = "'" . mysql_real_escape_string($_POST['details']) . "'";
    }
    $propa_url = 'null';
    if (isset($_POST['propa_url']) && $_POST['propa_url'] != "") {
        $propa_url = "'" . mysql_real_escape_string($_POST['propa_url']) . "'";
    }
    $hostname = 'null';
    if (isset($_POST['hostname']) && $_POST['hostname'] != "") {
        $hostname = "'" . mysql_real_escape_string($_POST['hostname']) . "'";
    }

    $forbid = false;
    $res = null;

    if (isset($_POST['event_id']) && $_POST['event_id'] != '') {

        $query = sprintf("select * from event_info where event_id='%s';",
            mysql_real_escape_string($_POST['event_id']));
        $res = mysql_query($query, $mysql);
        $row = mysql_fetch_assoc($res);
        if ($row['username'] == $username) {
            $query = sprintf("update event_info set title='%s', speaker=%s, date_type='%s', date='%s',
                location='%s', hostname=%s, category='%s', register=%s, register_date_type='%s', register_date='%s',
                notification=%s, details=%s, propa_url=%s, edit_time=null where event_id=%s;",
                mysql_real_escape_string($_POST['title']),
                $speaker,
                mysql_real_escape_string($_POST['date_type']),
                $date,
                mysql_real_escape_string($_POST['location']),
                $hostname,
                mysql_real_escape_string($_POST['category']),
                $register,
                mysql_real_escape_string($_POST['register_date_type']),
                $register_date,
                $notification,
                $details,
                $propa_url,
                $_POST['event_id']);

        } else {
            $forbid = true;
        }

    } else {

        $res_tmp = true;
        $short_url = null;
        while ($res_tmp) {
            $short_url = generate_short_url(4);
            $query = sprintf("select * from event_info where short_url='%s' limit 1;", $short_url);
            $res_tmp = mysql_query($query, $mysql);
            $res_tmp = mysql_num_rows($res_tmp) == 0 ? false : true;
        }

        $query = sprintf("insert into event_info value (null,'%s', '%s', %s, '%s', %s, '%s', '%s', '%s', %s, '%s', '%s', %s, %s, '%s', %s, null, null);",
            mysql_real_escape_string($_POST['title']),
            $username,
            $speaker,
            mysql_real_escape_string($_POST['location']),
            $hostname,
            mysql_real_escape_string($_POST['date_type']),
            $date,
            mysql_real_escape_string($_POST['category']),
            $register,
            mysql_real_escape_string($_POST['register_date_type']),
            $register_date,
            $notification,
            $details,
            $short_url,
            $propa_url);
    }

    if (!$forbid) $res = mysql_query($query, $mysql);

    ?>
    <div class="weui_mask"></div>
    <div class="weui_dialog">
        <div class="weui_dialog_hd">
            <strong class="weui_dialog_title">保存结果</strong>
        </div>
        <div class="weui_dialog_bd">
            <?php
            if ($forbid) {
                echo "禁止保存不是自己的活动!";
            } else {
                if ($res) {
                    echo "活动保存成功! 点击“确定”跳转到活动管理";
                } else {
                    echo "活动保存失败! 点击“确定”返回编辑界面<br>错误代码<br>" . mysql_error() . "<br>请发送错误代码联系管理员fdutopia@lyq.me";
                }
            }
            ?>
        </div>
        <div class="weui_dialog_ft">
            <a href="
            <?php
                if ($forbid) {
                    echo "javascript:history.back();";
                } else {
                    if ($res) {
                        echo "manager.php#m/0";
                    } else {
                        echo "javascript:history.back();";
                    }
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
            <a href="javascript:history.back()" class="weui_btn_dialog primary">确定</a>
        </div>
    </div>
    <?php
}

?>
    </body>
    </html>
