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

    <script type="text/javascript" src="js/event.js"></script>

    <title>保存一则回顾 | FDUTOPIA</title>
</head>

<body ontouchstart>
<?php

if (isset($_POST['event_id']) && $_POST['event_id'] != '') {

    $forbid = false;

    $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
    mysql_query("set names 'utf8'");
    mysql_select_db("fudan_info");

    $query = sprintf("SELECT * FROM event_info WHERE event_id='%s';",
        mysql_real_escape_string($_POST['event_id']));
    $res = mysql_query($query, $mysql);
    $row = mysql_fetch_assoc($res);
    if ($row['username'] == $username) {
        $review_url = 'null';
        if (isset($_POST['review_url']) && $_POST['review_url'] != '') {
            preg_match_all('[\S+]', $_POST['review_url'], $matches);
            if (strlen($matches[0][0]) > 0) {
                $review_url = "'" . mysql_real_escape_string($matches[0][0]) . "'";
            }
        }
        $query = sprintf("UPDATE event_info SET review_url=%s WHERE event_id=%s;",
            $review_url,
            $_POST['event_id']);

    } else {
        $forbid = true;
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
                echo "禁止保存不是自己活动的回顾!";
            } else {
                if ($res) {
                    echo "回顾保存成功! 点击“确定”跳转到我的历史发布";
                } else {
                    echo "回顾保存失败! 点击“确定”返回编辑界面<br>错误代码<br>" . mysql_error() . "<br>请发送错误代码联系管理员fdutopia@lyq.me";
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
                    echo "manager.php#m/page_events";
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
            <a href="manager.php#m/page_events" class="weui_btn_dialog primary">确定</a>
        </div>
    </div>
    <?php
}

?>
</body>
</html>
