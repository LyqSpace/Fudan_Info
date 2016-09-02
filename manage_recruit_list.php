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

    <title>我的招新系统 | FDUTOPIA</title>
</head>

<body ontouchstart>
<div class="page_header">
    <h1 class="page_title">我的招新系统</h1>

    <p class="page_desc">在“招新管理”里编辑招新表</p>
</div>
<div class="page_body">

    <div class="weui_btn_area">
        <a class="weui_btn weui_btn_plain_default" href="manager.php#m/page_recruits">返回招新管理</a>
    </div>

    <div class="weui_cells weui_cells_form">
        <div class="weui_cell">
            <table class="dataintable">
                <tbody>
                <tr>
                    <th style="min-width: 4em">用户信息</th>
                    <th>报名信息</th>
                </tr>
                <?php

                $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
                mysql_query("set names 'utf8'");
                mysql_select_db("fudan_info");

                $query = sprintf("UPDATE recruit_list SET new_increment=FALSE WHERE username='%s';", $username);
                $res = mysql_query($query, $mysql);

                $query = sprintf("SELECT * FROM recruit_list WHERE username='%s' ORDER BY recruit_register_time DESC;", $username);
                //echo $query;
                $res = mysql_query($query, $mysql);
                while ($row = mysql_fetch_assoc($res)) {
                    ?>
                    <tr>
                        <td>
                            <ul>
                                <?php
                                echo "<li>【姓名】" . $row['guest_name'] . "</li>";
                                echo "<li>【学号】" . $row['guest_id'] . "</li>";
                                echo "<li>【手机】" . $row['guest_phone'] . "</li>";
                                ?>
                            </ul>
                        </td>
                        <td>
                            <ul>
                                <?php
                                echo "<li>【专业】" . $row['guest_major'] . "</li>";
                                if ($row['guest_message'] != '') {
                                    echo "<li>【留言】" . $row['guest_message'] . "</li>";
                                }
                                if ($row['join_management']) {
                                    echo "<li id='info_green'>愿意加入管理层</li>";
                                }
                                $register_date = date('n月j日 H:i', strtotime($row['recruit_register_time']));
                                echo "<li>" . $register_date . "</li>";
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
<br>
</body>
</html>
