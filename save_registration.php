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
    <meta name="keywords" content="Fudan, Informations">
    <meta name="author" content="Liang Yongqing, Liu Xueyue">
    <link rel="stylesheet" type="text/css" href="weui.min.css" />
    <link rel="stylesheet" type="text/css" href="style.css" />
    <script type="text/javascript" src="functions.js"></script>
    <title>保存报名表 | FDUTOPIA</title>
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

if (isset($_POST['event_id']) && $_POST['event_id'] != '') {

    $forbid = false;
    $error_msg = "";

    $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
    mysql_query("set names 'utf8'");
    mysql_select_db("fudan_info");

    $query = sprintf("select * from event_info where event_id='%s';",
        mysql_real_escape_string($_POST['event_id']));
    $res = mysql_query($query, $mysql);
    $row = mysql_fetch_assoc($res);
    if ($row['username'] == $username) {

        $register = 'false';
        if (isset($_POST['register']) && $_POST['register'] == "on") {
            $register = 'true';
        }
        $register_date = mysql_date_format($_POST['register_date']);

        $query = sprintf("update event_info set register=%s, register_date_type='%s', register_date='%s' where event_id=%s;",
            $register,
            mysql_real_escape_string($_POST['register_date_type']),
            $register_date,
            mysql_real_escape_string($_POST['event_id']));

        echo $query;
        $res = mysql_query($query, $mysql);
        if (!$res) $error_msg += "保存“是否报名”失败!<br>";

        $registration_id = 'false';
        if (isset($_POST['registration_id']) && $_POST['registration_id'] == "on") {
            $registration_id = 'true';
        }
        $registration_name = 'false';
        if (isset($_POST['registration_name']) && $_POST['registration_name'] == "on") {
            $registration_name = 'true';
        }
        $registration_major = 'false';
        if (isset($_POST['registration_major']) && $_POST['registration_major'] == "on") {
            $registration_major = 'true';
        }
        $registration_phone = 'false';
        if (isset($_POST['registration_phone']) && $_POST['registration_phone'] == "on") {
            $registration_phone = 'true';
        }

        $query = sprintf("select * from event_registration where event_id=%s;",
            mysql_real_escape_string($_POST['event_id']));
        $res = mysql_query($query, $mysql);
        if (mysql_num_rows($res)) {

            $query = sprintf("update event_registration set registration_id=%s,registration_name=%s,registration_major=%s,
                registration_phone=%s, ticket_per_person='%s' where event_id=%s;",
                $registration_id,
                $registration_name,
                $registration_major,
                $registration_phone,
                mysql_real_escape_string($_POST['ticket_per_person']),
                mysql_real_escape_string($_POST['event_id']));
        } else {
            $query = sprintf("insert into event_registration value(%s,%s,%s,%s,%s,'%s');",
                mysql_real_escape_string($_POST['event_id']),
                $registration_id,
                $registration_name,
                $registration_major,
                $registration_phone,
                mysql_real_escape_string($_POST['ticket_per_person']));
        }
        echo $query;
        $res = mysql_query($query, $mysql);
        if (!$res) $error_msg += "保存“报名表基本信息”失败!<br>";

        $date_format = "date_";
        $date_cnt = 1;
        $date_id = $date_format . $date_cnt;

        $ticket_format = "ticket_count_";
        $ticket_count = 1;
        $ticket_id = $ticket_format . $ticket_count;

        $query = sprintf("select * from event_date where event_id=%s;",
            mysql_real_escape_string($_POST['event_id']));
        echo $query;
        $res = mysql_query($query, $mysql);
        $old_record_cnt = mysql_num_rows($res);
        $old_record_id = 0;
        for ($old_record_id = 0; $old_record_id < $old_record_cnt; $old_record_id++) {

            $row = mysql_fetch_assoc($res);

            if (!isset($_POST[$date_id])) break;

            $query = sprintf("update event_date set event_date='%s', ticket_count=%s where registration_serial='%s';",
                mysql_date_format($_POST[$date_id]),
                mysql_real_escape_string($_POST[$ticket_id]),
                $row['registration_serial']);
            echo $query;
            mysql_query($query, $mysql);

            $date_cnt++;
            $date_id = $date_format . $date_cnt;

            $ticket_count++;
            $ticket_id = $ticket_format . $ticket_count;

        }

        while (isset($_POST[$date_id])) {
            $query = sprintf("insert into event_date value (null, %s, '%s', '%s', 0);",
                mysql_real_escape_string($_POST['event_id']),
                mysql_date_format($_POST[$date_id]),
                mysql_real_escape_string($_POST[$ticket_id]));
            echo $query;
            mysql_query($query, $mysql);

            $date_cnt++;
            $date_id = $date_format . $date_cnt;

            $ticket_count++;
            $ticket_id = $ticket_format . $ticket_count;
        }

        for (;$old_record_id < $old_record_cnt; $old_record_id++) {
            $query = sprintf("delete from event_date where registration_serial='%s';", $row['registration_serial']);
            echo $query;
            mysql_query($query, $mysql);
            $row = mysql_fetch_assoc($res);
        }

    } else {
        $forbid = true;
    }

    ?>
    <div class="weui_mask"></div>
    <div class="weui_dialog">
        <div class="weui_dialog_hd">
            <strong class="weui_dialog_title">保存结果</strong>
        </div>
        <div class="weui_dialog_bd">
            <?php
            if ($forbid) {
                echo "禁止保存不是自己活动的报名表!";
            } else {
                if ($error_msg == "") {
                    echo "报名表保存成功! 点击“确定”跳转到我的历史发布";
                } else {
                    echo "报名表保存失败! 点击“确定”返回编辑界面<br>错误代码<br>" . mysql_error() . "<br>请发送错误代码联系管理员fdutopia@lyq.me";
                }
            }
            ?>
        </div>
        <div class="weui_dialog_ft">
            <a href="
            <?php
            if ($forbid) {
                echo "index.php";
            } else {
                if ($error_msg == "") {
                    echo "client_history.php";
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
            <a href="index.php" class="weui_btn_dialog primary">确定</a>
        </div>
    </div>
    <?php
}

?>
</body>
</html>
