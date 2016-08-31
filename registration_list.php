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

    <title>我的票务系统 | FDUTOPIA</title>
</head>

<body ontouchstart>
<div class="page_header">
    <h1 class="page_title">我的票务系统</h1>
    <p class="page_desc">点击“场次”查看具体报名列表</p>
</div>
<div class="page_body">

    <div class="weui_btn_area">
        <a class="weui_btn weui_btn_plain_default" href="manager.php#m/page_events">返回活动管理</a>
    </div>
    <?php
        $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
        mysql_query("set names 'utf8'");
        mysql_select_db("fudan_info");

        $query = sprintf("select * from event_info natural join event_registration_common where register=true and confirm=true and username='%s' order by event_id desc;",
            $username);
        //echo $query;
        $event_list = mysql_query($query, $mysql);
        $event_cnt = 0;

        while ($event_info = mysql_fetch_assoc($event_list)) {

    ?>
    <div class="weui_cells weui_cells_form">
        <div class="weui_cells_title"><?php
            $event_cnt++;
            echo $event_cnt . ". " . $event_info['title'];
        ?></div>
        <div class="weui_cells weui_cells_access">
        <?php
            $query = sprintf("select * from event_registration_common natural join event_registration_date where event_id='%s';", $event_info['event_id']);
            //echo $query;
            $registration_list = mysql_query($query, $mysql);
            $registration_cnt = 0;
            while ($registration_info = mysql_fetch_assoc($registration_list)) {
                $event_date = date('Y年n月j日 H:i', strtotime($registration_info['event_date']));

                $query = sprintf("select count(*) as cnt from event_registration_list where new_increment=TRUE and registration_serial='%s';", $registration_info['registration_serial']);
                //echo $query;
                $new_increment_res = mysql_query($query, $mysql);
                $new_increment_row = mysql_fetch_assoc($new_increment_res);
                $new_increment_num = '';
                if ($new_increment_row['cnt'] > 0) {
                    $new_increment_num = '(' . $new_increment_row["cnt"] . ')';
                }
        ?>
            <a class="weui_cell" href="registration_single.php?registration_serial=<?php echo $registration_info['registration_serial'];?>">
                <div class="weui_cell_bd weui_cell_primary">
                    <p>【场次<?php
                        $registration_cnt++;
                        echo $registration_cnt . '】' . $event_date . $new_increment_num;
                    ?></p>
                </div>
                <div class="weui_cell_ft">查看报名名单</div>
            </a>
        <?php
            }
        ?>
        </div>
        <?php
        }
    ?>
    </div>
</div>
<br>
</body>
</html>
