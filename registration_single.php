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
    <title>报名名单 | FDUTOPIA</title>
</head>

<body ontouchstart>

<?php

if (isset($_GET['registration_serial']) && $_GET['registration_serial'] != '') {

    $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
    mysql_query("set names 'utf8'");
    mysql_select_db("fudan_info");

    $query = sprintf("select * from event_info natural join event_date where registration_serial='%s';",
        mysql_real_escape_string($_GET['registration_serial']));
    //echo $query;
    $res = mysql_query($query, $mysql);
    $row = mysql_fetch_assoc($res);

    if ($row['username'] == $username) {

        $event_date = date('Y年n月j日 H:i', strtotime($row['event_date']));
        ?>
    <div class="page_header">
        <h1 class="page_title">报名名单</h1>
        <p class="page_desc"><?php echo '【活动】' . $row['title'];?></p>
        <p class="page_desc"><?php echo '【场次】' . $event_date;?></p>
    </div>
    <div class="page_body">
        <div class="weui_btn_area">
            <a class="weui_btn weui_btn_plain_default" href="registeration_list.php">返回我的票务系统</a>
        </div>
        <div class="weui_cells weui_cells_form">
            <div class="weui_cell">
            <table class="dataintable">
                <tbody>
                    <tr>
                        <th>入场码</th>
                        <th>用户信息</th>
                    </tr>
        <?php

        $query = sprintf("select * from event_register_list where registration_serial='%s' order by register_serial;",
            mysql_real_escape_string($_GET['registration_serial']));
        //echo $query;
        $res = mysql_query($query, $mysql);
        while ($row = mysql_fetch_assoc($res)) {
        ?>
                    <tr>
                        <td><?php echo $row['register_serial'];?></td>
                        <td>
                            <ul>
                                <?php
                                if ($row['register_id'] != '') {
                                    echo "<li>【卡号】". $row['register_id'] ."</li>";
                                }
                                if ($row['register_name'] != '') {
                                    echo "<li>【姓名】". $row['register_name'] ."</li>";
                                }
                                if ($row['register_major'] != '') {
                                    echo "<li>【专业】". $row['register_major'] ."</li>";
                                }
                                if ($row['register_phone'] != '') {
                                    echo "<li>【手机】". $row['register_phone'] ."</li>";
                                }
                                if ($row['ticket_num'] != '') {
                                    echo "<li>【票数】". $row['ticket_num'] ."</li>";
                                }
                                if ($row['message'] != '') {
                                    echo "<li>【留言】". $row['message'] ."</li>";
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
                <a href="index.php" class="weui_btn_dialog primary">确定</a>
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
            <a href="index.php" class="weui_btn_dialog primary">确定</a>
        </div>
    </div>
    <?php
}
?>
<br>
</body>
</html>
