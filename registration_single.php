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

    <title>报名名单 | FDUTOPIA</title>
</head>

<body ontouchstart>

<?php

if (isset($_GET['registration_serial']) && $_GET['registration_serial'] != '') {

    $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
    mysql_query("set names 'utf8'");
    mysql_select_db("fudan_info");

    $query = sprintf("UPDATE event_registration_list SET new_increment=FALSE WHERE registration_serial='%s';", $_GET['registration_serial']);
    $res = mysql_query($query, $mysql);

    $query = sprintf("SELECT * FROM event_info NATURAL JOIN event_registration_date WHERE registration_serial='%s';",
        mysql_real_escape_string($_GET['registration_serial']));
    //echo $query;
    $res = mysql_query($query, $mysql);
    $row = mysql_fetch_assoc($res);

    if ($row['username'] == $username) {

        $event_date = date('Y年n月j日 H:i', strtotime($row['event_date']));
        ?>
        <div class="page_header">
            <h1 class="page_title">报名名单</h1>

            <p class="page_desc"><?php echo '【活动】' . $row['title']; ?></p>

            <p class="page_desc"><?php echo '【场次】' . $event_date; ?></p>
        </div>
        <div class="page_body">
            <div class="weui_btn_area">
                <a class="weui_btn weui_btn_plain_default" href="registration_list.php">返回我的票务系统</a>
            </div>
            <div class="weui_cells weui_cells_form">
                <div class="weui_cell">
                    <table class="dataintable">
                        <tbody>
                        <tr>
                            <th class="first_th">入场码</th>
                            <th>用户信息</th>
                        </tr>
                        <?php

                        $query = sprintf("SELECT * FROM event_registration_list WHERE registration_serial='%s' ORDER BY registration_user_serial;",
                            mysql_real_escape_string($_GET['registration_serial']));
                        //echo $query;
                        $res = mysql_query($query, $mysql);
                        while ($row = mysql_fetch_assoc($res)) {
                            ?>
                            <tr>
                                <td><?php echo $row['registration_user_serial']; ?></td>
                                <td>
                                    <ul>
                                        <?php
                                        echo "<li>【学号】" . $row['registration_id'] . "</li>";
                                        echo "<li>【姓名】" . $row['registration_name'] . "</li>";
                                        echo "<li>【专业】" . $row['registration_major'] . "</li>";
                                        echo "<li>【手机】" . $row['registration_phone'] . "</li>";
                                        echo "<li>【票数】" . $row['ticket_num'] . "</li>";
                                        if ($row['registration_message'] != '') {
                                            echo "<li>【留言】" . $row['registration_message'] . "</li>";
                                        }
                                        ?>
                                    </ul>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php
    } else {
        ?>
        <div class="weui_mask"></div>
        <div class="weui_dialog">
            <div class="weui_dialog_hd">
                <strong class="weui_dialog_title">违规访问</strong>
            </div>
            <div class="weui_dialog_bd">
                禁止查看不是自己的票务系统!
            </div>
            <div class="weui_dialog_ft">
                <a href="manager.php#m/page_events" class="weui_btn_dialog primary">确定</a>
            </div>
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
            <a href="manager.php#m/page_events" class="weui_btn_dialog primary">确定</a>
        </div>
    </div>
    <?php
}
?>
<br>
</body>
</html>
